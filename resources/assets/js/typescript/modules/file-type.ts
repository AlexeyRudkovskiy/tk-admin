import {FilePopup} from "../popups/file-popup";
import {PopupSize} from "../popup";

let uploadXhr: XMLHttpRequest = null;

export function fileType() {
  const fileTypes = [...document.querySelectorAll('.file-type-container')];
  const filePopup: FilePopup = FilePopup.getInstance(FilePopup);
  filePopup.size = PopupSize.LARGE;

  for (let fileType of fileTypes) {
    const fileInput: HTMLInputElement = fileType.querySelector('input[type="file"]') as HTMLInputElement;
    const hiddenInput: HTMLInputElement = fileType.querySelector('input[type="hidden"]');
    const uploadButton: HTMLDivElement = fileType.querySelector('.upload-file');
    const selectFile: HTMLDivElement = fileType.querySelector('.select-file');
    const previewProgress: HTMLDivElement = fileType.querySelector('.preview-status-progress .progress-bar');
    const previewDelete: HTMLElement = fileType.querySelector('.preview-actions .preview-delete');
    const previewImage: HTMLImageElement = fileType.querySelector('.preview > img') as HTMLImageElement;
    const metaSizeValue: HTMLSpanElement = fileType.querySelector('.meta-size-value');
    const previewFilename: HTMLDivElement = fileType.querySelector('.preview-filename');

    const uploadFileCallback = (data) => {
      const uploadedFile = data[0];

      previewImage.src = uploadedFile.thumbnails_paths['150x150'];
      (previewFilename as any).innerText = uploadedFile.original_name;
      (metaSizeValue as any).innerText = uploadedFile.size;
      hiddenInput.value = uploadedFile.id;

      fileType.classList.add('uploaded');
    };

    const uploadFileProgressCallback = (percents) => {
      fileType.classList.remove('no-preview');
      previewProgress.style.width = percents + '%';
    };

    const processFiles = (files) => {
      if (files.length > 0) {
        const file = files[0];
        let filename: any = file.name.split('.');
        filename.pop();
        filename = filename.join('.');

        (previewFilename as any).innerText = filename;
        fileType.classList.add('no-preview');

        fileType.classList.remove('not-selected', 'uploaded');

        uploadFile(file, uploadFileCallback, uploadFileProgressCallback);
      }
    };

    uploadButton.addEventListener('click', () => fileInput.click());

    uploadButton.addEventListener('dragover', (e) => {
      e.preventDefault();
    });

    uploadButton.addEventListener('dragleave', (e) => {
      e.preventDefault();
    });

    fileInput.addEventListener('change', (e) => {
      const files = fileInput.files;
      processFiles(files);
    });

    uploadButton.addEventListener('drop', (e: DragEvent) => {
      e.preventDefault();
      const files = e.dataTransfer.files;
      processFiles(files);
    });

    selectFile.addEventListener('click', () => {
      filePopup.selectFile('image', (file) => {
        filePopup.hide();

        previewImage.src = file.thumbnails_paths['150x150'];
        (previewFilename as any).innerText = file.original_name;
        (metaSizeValue as any).innerText = file.size;
        hiddenInput.value = file.id;

        fileType.classList.remove('not-selected');
        fileType.classList.add('uploaded');
      });
    });

    previewDelete.addEventListener('click', () => {
      fileType.classList.add('not-selected');
      hiddenInput.value = null;
    });

  }
}

function uploadFile(file, complete, progress) {
  if (uploadXhr !== null) {
    uploadXhr.abort();
    uploadXhr = null;
  }

  uploadXhr = new XMLHttpRequest();
  const formData = new FormData();

  formData.append('file[]', file);

  uploadXhr.open('POST', '/admin/media', true);
  uploadXhr.setRequestHeader('X-CSRF-TOKEN', (window as any)._csrf_token);

  uploadXhr.upload.addEventListener('progress', (e) => {
    const percentComplete = (e.loaded / e.total) * 100;
    progress.call(window, percentComplete);
  });

  uploadXhr.addEventListener('readystatechange', () => {
    if (uploadXhr.readyState === 4 && uploadXhr.status === 200) {
      const data = JSON.parse(uploadXhr.responseText);
      complete.call(window, data);
    }
  });

  uploadXhr.send(formData);
}

