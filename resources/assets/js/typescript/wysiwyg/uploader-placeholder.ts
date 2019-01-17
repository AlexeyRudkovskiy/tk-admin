import {Gallery} from "./gallery";

export class UploaderPlaceholder {

  private uploaded: any = [];

  public constructor(private element: HTMLDivElement, private files: FileList) {
    this.element.setAttribute('contenteditable', 'false');
  }

  public createPlaceholder() {
    this.clear();
    for (let i = 0; i < this.files.length; i++) {
      const file = this.files.item(i);
      this.createForFile(file, i);
    }
  }

  public clear() {
    while (this.element.firstElementChild !== null) {
      this.element.removeChild(this.element.firstElementChild);
    }
  }

  private createForFile(file: File, index: number) {
    const placeholder = document.createElement('div');
    const filename = document.createElement('div');
    const hint = document.createElement('div');
    const progress = document.createElement('div');
    const progressBar = document.createElement('div');
    placeholder.classList.add('uploader-placeholder');
    filename.classList.add('filename');
    hint.classList.add('hint');
    progress.classList.add('progress');
    progressBar.classList.add('progress-bar');

    progressBar.style.width = '0%';

    progress.appendChild(progressBar);
    placeholder.appendChild(filename);
    placeholder.appendChild(hint);
    placeholder.appendChild(progress);

    this.element.appendChild(placeholder);

    this.upload(file, (uploaded) => {
      this.element.removeChild(placeholder);
      this.uploaded[index] = uploaded[0];
      this.processUploaded();
    }, (percents) => {
      progressBar.style.width = percents + '%';
      if (percents === 100) {
        progressBar.classList.add('progress-bar-striped');
        progressBar.classList.add('progress-bar-animated');
      }
    })
  }

  private upload(file, onSuccess = null, onProgress = null) {
    const xhr = new XMLHttpRequest();
    const formData = new FormData();
    formData.append('file[]', file);
    xhr.open('POST', '/admin/media/', true);
    const csrfMeta: HTMLMetaElement = document.querySelector('meta[name="csrf_token"]') as HTMLMetaElement;
    xhr.setRequestHeader('X-CSRF-TOKEN', csrfMeta.content);
    if (onSuccess !== null) {
      xhr.addEventListener('readystatechange', () => {
        if (xhr.readyState === 4 && xhr.status === 200) {
          const response = xhr.responseText;
          onSuccess(JSON.parse(response));
        }
      });
    }
    if (onProgress !== null) {
      xhr.upload.addEventListener('progress', (e: any) => {
        const percentComplete = (e.loaded / e.total) * 100;
        onProgress(percentComplete);
      });
    }
    xhr.send(formData);
  }

  private processUploaded() {
    if (this.uploaded.length !== this.files.length) {
      return;
    }

    if (this.uploaded.length === 1) {
      this.insertSingleImage(this.uploaded[0]);
    } else {
      this.insertGallery();
    }
  }

  private insertSingleImage(file) {
    const imageWrapper = document.createElement('div');
    const image = document.createElement('img');

    imageWrapper.classList.add('image-wrapper');
    imageWrapper.setAttribute('contenteditable', 'false');

    image.src = file.thumbnails_paths.default;
    image.classList.add('zoomable-image');
    image.setAttribute('data-image', file.full_path);

    imageWrapper.appendChild(image);

    this.element.parentElement.replaceChild(imageWrapper, this.element);
  }

  private insertGallery() {
    const wrapper = document.createElement('div');
    wrapper.classList.add('post-gallery');
    wrapper.setAttribute('contenteditable', 'false');

    for (let file of this.uploaded) {
      const itemWrapper = document.createElement('div');
      const image = document.createElement('img');

      itemWrapper.classList.add('gallery-item');
      itemWrapper.setAttribute('data-image', file.full_path);

      image.src = file.thumbnails_paths['gallery-item'];
      itemWrapper.appendChild(image);
      wrapper.appendChild(itemWrapper);
    }

    const gallery = new Gallery(wrapper);

    this.element.parentElement.replaceChild(wrapper, this.element);
  }
}

export function createUploader (placeholder: HTMLDivElement, files: FileList) {
  return new UploaderPlaceholder(placeholder, files);
}