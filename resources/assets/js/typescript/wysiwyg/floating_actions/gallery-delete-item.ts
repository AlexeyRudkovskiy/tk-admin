import {AbstractAction} from "./abstract-action";

export class GalleryDeleteItem extends AbstractAction {

  create() {
    this.createDefaultAnchor('видалити зображення з галереї')
      .addEventListener('click', () => {
        this.currentElement.parentElement.removeChild(this.currentElement);
        this.editor.hideFloatingActionPanel();
      });
  }

  getIdentifier(): string {
    return "gallery.delete_item";
  }

  getTargetElements(): string[] {
    return ['div'];
  }


  shouldBeDisplayed(element: HTMLElement): boolean {
    return element.classList.contains('gallery-item');
  }
}