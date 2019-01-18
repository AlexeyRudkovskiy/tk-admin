import {AbstractAction} from "./abstract-action";

export class ImageEdit extends AbstractAction {

  create() {
    this.createDefaultAnchor('редагувати')
      .addEventListener('click', () => alert('Editing'));
  }

  getIdentifier(): string {
    return "image.edit";
  }

  getTargetElements(): string[] {
    return [ 'img' ];
  }

}