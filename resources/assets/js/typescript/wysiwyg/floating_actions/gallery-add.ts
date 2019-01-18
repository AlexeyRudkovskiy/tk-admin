import {AbstractAction} from "./abstract-action";
import {FilePopup} from "../../popups/file-popup";
import {PopupSize} from "../../popup";

export class GalleryAdd extends AbstractAction {

  private filePopup: FilePopup = null;

  create() {
    this.createDefaultAnchor('додати зображення')
      .addEventListener('click', () => {
        this.filePopup.header = 'Select files';
        this.filePopup.size = PopupSize.FULLPAGE;
        this.filePopup.selectFiles('image', (files) => {
          this.editor.restoreSelection();

          for (let file of files) {
            const wrapper = document.createElement('div');
            const image = document.createElement('img');

            wrapper.classList.add('gallery-item');
            wrapper.setAttribute('data-image', file.full_path);

            image.src = file.thumbnails_paths['150x150'];
            wrapper.appendChild(image);

            image.addEventListener('load', () => {
              image.style.height = image.offsetWidth + 'px';
            });

            this.currentElement.parentElement.appendChild(wrapper);
          }

          this.filePopup.hide();
        });
      });

    this.filePopup = FilePopup.getInstance(FilePopup);
  }

  getIdentifier(): string {
    return "gallery.add";
  }

  getTargetElements(): string[] {
    return [ 'div' ];
  }

  shouldBeDisplayed(element: HTMLElement): boolean {
    let galleryDiv = element;
    let i = 3;
    while (!galleryDiv.classList.contains('post-gallery') && i > 0) {
      galleryDiv = galleryDiv.parentElement;
      i--;
    }

    return galleryDiv.classList.contains('post-gallery');
  }

}