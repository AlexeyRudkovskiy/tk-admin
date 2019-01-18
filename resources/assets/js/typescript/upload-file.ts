import {FilePopup} from "./popups/file-popup";

export function uploadFile() {

  const mediaUploadDragContainer = document.querySelector('.filebrowser');
  let mediaUploadContainer = document.querySelector('.media-upload');

  let isShown: boolean = false;

  if (!mediaUploadDragContainer.hasAttribute('data-upload-processed')) {
    mediaUploadDragContainer.addEventListener('dragover', (e) => {
      e.preventDefault();

      if (!isShown) {
        mediaUploadContainer.classList.remove('hidden');
        isShown = true;
      }
    });

    mediaUploadContainer.addEventListener('dragleave', (e: any) => {
      e.preventDefault();

      if (isShown && e.target === mediaUploadContainer && e.path.indexOf(mediaUploadContainer) < 1) {
        isShown = false;
        mediaUploadContainer.classList.add('hidden');
      }
    });

    mediaUploadContainer.addEventListener('drop', (e: DragEvent) => {
      e.preventDefault();

      const files = e.dataTransfer.files;
      const formData = new FormData();
      for (let i = 0; i < files.length; i++) {
        formData.append('file[]', files[i]);
      }

      if (files.length > 0) {
        const xhr = new XMLHttpRequest();
        const popup: FilePopup = FilePopup.getInstance(FilePopup);
        xhr.open('POST', '/admin/media?folder=' + popup.folder, true);
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        xhr.setRequestHeader('X-CSRF-TOKEN', (window as any)._csrf_token);

        xhr.addEventListener('readystatechange', () => {
          if (xhr.readyState === 4 && xhr.status === 200) {
            popup.reload();
            mediaUploadContainer.classList.add('hidden');
            isShown = false;
          }
        });

        xhr.send(formData);
      } else {
        mediaUploadContainer.classList.add('hidden');
        isShown = false;
      }
    });

    mediaUploadDragContainer.setAttribute('data-upload-processed', 'yes');
  }

}
