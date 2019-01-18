import {AbstractAction} from "./abstract-action";
import {FilePopup} from "../../popups/file-popup";

export class ImageChange extends AbstractAction {

  private popup: FilePopup = null;

  public constructor() {
    super();

    this.popup = FilePopup.getInstance(FilePopup);
  }

  create() {
    this.anchor = document.createElement('a');
    this.anchor.innerHTML = 'змінити';
    this.anchor.href = 'javascript:';
    this.anchor.addEventListener('click', () => {
      this.popup.selectFile('image', (file) => {
        this.currentElement.setAttribute('src', this.popup.getFilePath(file));
        this.popup.hide();
      });
      this.popup.show();
    });
  }

  getIdentifier(): string {
    return "image.change";
  }

  getTargetElements(): string[] {
    return [ 'img' ];
  }

}