import {AbstractAction} from "./abstract-action";

export class ImageDelete extends AbstractAction {

  create() {
    this.createDefaultAnchor('видалити зображення');
    this.anchor.addEventListener('click', () => {
      const imageWrapper = this.currentElement.parentElement;
      if (imageWrapper.tagName === 'DIV' && imageWrapper.classList.contains('image-wrapper')) {
        imageWrapper.parentElement.removeChild(imageWrapper);
        this.editor.hideFloatingActionPanel();
      }
    });
  }

  getIdentifier(): string {
    return "image.delete";
  }

  getTargetElements(): string[] {
    return [ 'img' ];
  }

}