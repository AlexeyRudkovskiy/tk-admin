import {AbstractAction} from "./abstract-action";
import {SimpleAction} from "./simple-action";
import {VideoPopup} from "../../popups/video-popup";
import {SingletonPopup} from "../../popups/singleton-popup";
import {PopupSize} from "../../popup";

export class ActionVideo extends SimpleAction {

  protected videoPopup: VideoPopup = null;

  getDescription(): string {
    return "Вставити відео";
  }

  getIcon(): string {
    return "fa fa-youtube";
  }

  getName(): string {
    return "toolbar.video";
  }

  performAction(): void {
    this.videoPopup.size = PopupSize.MEDIUM;
    this.videoPopup.showForResult(result => {
      this.editor.restoreSelection();

      const insertHtml = '<p>&nbsp;</p><p class="post-video" contenteditable="false">' + result.code + '</p><p>&nbsp;</p>';
      document.execCommand('insertHTML', false, insertHtml);
    });
  }

  prepare(): void {
    super.prepare();

    this.videoPopup = VideoPopup.getInstance(VideoPopup);
  }

}