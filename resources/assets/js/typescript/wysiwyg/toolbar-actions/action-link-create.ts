import {AbstractAction} from "./abstract-action";
import {LinkSettingsPopup} from "../../popups/link-settings-popup";
import {PopupSize} from "../../popup";

export class ActionLinkCreate extends AbstractAction {

  private linkSettingsPopup: LinkSettingsPopup = null;

  private isLink: boolean = false;

  private selection: any = null;

  getDescription(): string {
    return "Додати посилання";
  }

  getIcon(): string {
    return "fa fa-link";
  }

  getName(): string {
    return "toolbar.link.create";
  }

  performAction() {
    this.linkSettingsPopup.header = 'Создание ссылки';
    this.linkSettingsPopup.showForResult(data => {
      this.editor.restoreSelection();

      for (let i = 0; i < this.selection.rangeCount; i++) {
        const range = this.selection.getRangeAt(i);
        const link = document.createElement('a');
        const linkContent = document.createTextNode(data.value);
        link.href = data.href;

        if (link.href.startsWith('http://') || link.href.startsWith('https://') || link.href.startsWith('//')) {
          link.target = '_blank';
        }

        if (typeof data.title !== "undefined" && data.title !== "undefined" && data.title.length > 0) {
          link.title = data.title;
        } else {
          link.title = data.value;
        }

        range.deleteContents();
        range.insertNode(link);
        link.appendChild(linkContent);
      }
    });

    this.selection = (window as any).getSelection();
    const currentText = this.selection.toString();

    this.linkSettingsPopup.data = {
      value: currentText || ''
    };
  }

  prepare(): void {
    this.linkSettingsPopup = LinkSettingsPopup.getInstance(LinkSettingsPopup);
    this.linkSettingsPopup.size = PopupSize.SMALL;
  }

  createToolbarElement(): HTMLDivElement {
    return this.createSimpleToolbarElement();
  }

  shouldUpdateToolbarElement(current: HTMLElement): boolean {
    const isLinkNow = current !== null && current.tagName.toLowerCase() === 'a';
    const shouldToggle = isLinkNow !== this.isLink;
    this.isLink = isLinkNow;

    return shouldToggle;
  }

  updateToolbarElement(): void {
    this.element.classList.toggle('active');
  }

}