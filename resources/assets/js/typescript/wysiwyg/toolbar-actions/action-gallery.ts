import {AbstractAction} from "./abstract-action";
import {Popup, PopupSize} from '../../popup'
import {FilePopup} from "../../popups/file-popup";
import {Gallery} from "../gallery";

export class ActionGallery extends AbstractAction {

  private filePopup: FilePopup = null;

  public constructor() {
    super();
    this.filePopup = FilePopup.getInstance(FilePopup);
  }

  getDescription(): string {
    return "Додати галерею";
  }

  getIcon(): string {
    return "icon-picture";
  }

  getName(): string {
    return "toolbar.gallery";
  }

  performAction() {
    this.filePopup.header = 'Select files';
    this.filePopup.size = PopupSize.FULLPAGE;
    this.filePopup.selectFiles('image', (files) => {
      this.editor.restoreSelection();
      const range = getSelection().getRangeAt(0);
      let target = range.commonAncestorContainer as any;
      while (typeof target.tagName === "undefined" || target.tagName.toLowerCase() !== 'p') {
        target = target.parentElement;
      }


      const galleryWrapper = document.createElement('div');
      galleryWrapper.classList.add('post-gallery');
      galleryWrapper.setAttribute('contenteditable', 'false');
      const gallery = new Gallery(galleryWrapper);
      target.parentElement.insertBefore(galleryWrapper, target.nextElementSibling);

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

        galleryWrapper.appendChild(wrapper);
      }

      this.filePopup.hide();
    });
  }

  prepare(): void {
    /// empty
  }

  shouldUpdateToolbarElement(current: HTMLElement): boolean {
    return false;
  }

  updateToolbarElement(): void {
  }

  createToolbarElement(): HTMLDivElement {
    return this.createSimpleToolbarElement();
  }

}