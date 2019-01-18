import {AbstractAction} from "./abstract-action";

export class GalleryDelete extends AbstractAction {

  create() {
    this.createDefaultAnchor('видалити галерею')
      .addEventListener('click', () => {
        let gallery = this.currentElement;
        while (!gallery.classList.contains('post-gallery')) {
          gallery = gallery.parentElement;
        }

        gallery.parentElement.removeChild(gallery);
        this.editor.hideFloatingActionPanel();
      });
  }

  getIdentifier(): string {
    return "";
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