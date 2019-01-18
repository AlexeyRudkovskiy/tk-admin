import {AbstractAction} from "./abstract-action";
import {LinkSettingsPopup} from "../../popups/link-settings-popup";

export class LinkEdit extends AbstractAction {

  private linkSettingsPopup: LinkSettingsPopup = null;

  create() {
    this.linkSettingsPopup = LinkSettingsPopup.getInstance(LinkSettingsPopup);

    this.anchor = document.createElement('a');
    this.anchor.href = 'javascript:';
    this.anchor.innerHTML = 'редагувати';
    this.anchor.addEventListener('click', () => {
      const href = this.currentElement.href;
      const value = this.currentElement.innerText;
      const title = this.currentElement.title;

      this.linkSettingsPopup.showForResult(data => this.updateLink(this.currentElement, data));
      this.linkSettingsPopup.data = { href, value, title };
    });
  }

  getIdentifier(): string {
    return "link.edit";
  }

  getTargetElements(): string[] {
    return [ 'a' ];
  }

  private updateLink(link, data) {
    link.href = data.href;
    if (typeof data.title !== "undefined" && data.title !== "undefined" && data.title.length > 0) {
      link.title = data.title;
    }

    if (link.href.startsWith('http://') || link.href.startsWith('https://') || link.href.startsWith('//')) {
      link.target = '_blank';
    }

    link.innerText = data.value;
  }

}