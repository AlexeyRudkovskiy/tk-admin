import {AbstractAction} from "./abstract-action";
import {Popup, PopupSize} from '../../popup'
import {FilePopup} from "../../popups/file-popup";

export class ActionMedia extends AbstractAction {

  private popup: FilePopup = null;

  private xhr: XMLHttpRequest = null;

  public constructor() {
    super();
  }

  getDescription(): string {
    return "Додати фото або зображення";
  }

  getIcon(): string {
    return "fa fa-image";
  }

  getName(): string {
    return "toolbar.media";
  }

  performAction() {
    this.popup.header = 'Simple';
    this.popup.size = PopupSize.FULLPAGE;

    this.popup.selectFile('image', (file) => {
      this.editor.restoreSelection();
      const thumbnailPath = this.popup.getThumbnailPath(file, 'default');
      const imagePath = this.popup.getFilePath(file);
      const insertHtml = '<p><br/></p><div class="image-wrapper" contenteditable="false"><img src="' + thumbnailPath + '" class="zoomable-image" data-image="' + imagePath +'" /></div><p><br/></p>';

      document.execCommand('insertHTML', false, insertHtml);
      this.popup.hide();
    });

    // this.getFilesAndFolders(null);
  }

  prepare(): void {
    this.popup = FilePopup.getInstance(FilePopup);
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