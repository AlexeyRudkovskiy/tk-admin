import {Action} from "./action";

export abstract class AbstractAction implements Action {

  protected editor: any = null;

  protected element: HTMLElement = null;

  protected onExecutedCallbacks = [];

  abstract getDescription(): string;

  abstract getIcon(): string;

  abstract getName(): string;

  abstract performAction();

  abstract prepare(): void;

  abstract shouldUpdateToolbarElement(current: HTMLElement): boolean;

  abstract createToolbarElement(): HTMLDivElement;

  abstract updateToolbarElement(): void;

  public disable(): void {
    this.element.setAttribute('disabled', 'disabled');
  }

  public enable(): void {
    this.element.removeAttribute('disabled');
  }

  public disabled(): boolean {
    return this.element.hasAttribute('disabled');
  }

  executeAction(): void {
    if (/AppleWebKit/.test(navigator.userAgent) || /Google Inc/.test(navigator.vendor)) {
      this.editor.restoreSelection();
    }

    if (!this.disabled()) {
      this.performAction();
    }
  }

  setEditor(editor): void {
    this.editor = editor;
  }

  setToolbarElement(element: HTMLElement): void {
    this.element = element;
  }

  protected getCtrlKey() {
    return navigator.platform.toLowerCase().indexOf("mac") > -1 ? 'command' : 'ctrl';
  }

  protected composeHotKey(hotKey: string) {
    return [this.getCtrlKey(), hotKey].join('+');
  }

  protected createSimpleToolbarElement(): HTMLDivElement {
    const item = document.createElement('div');
    const itemIcon = document.createElement('i');
    item.classList.add('wysiwyg-toolbar-item');
    item.setAttribute('data-hint', this.getDescription());

    this.getIcon().split(' ')
      .forEach(itemIconClass => itemIcon.classList.add(itemIconClass));

    item.addEventListener('click', () => {
      this.executeAction();
      this.execOnExecutedEvents();
    });

    item.appendChild(itemIcon);

    return item;
  }

  protected getComputedStyleProperty(el: any, propName: string) {
    if ((window as any).getComputedStyle) {
      return (window as any).getComputedStyle(el, null)[propName];
    } else if (el.currentStyle) {
      return el.currentStyle[propName];
    }
  }

  protected execOnExecutedEvents() {
    this.onExecutedCallbacks
      .forEach(callable => callable.call(window, this));
  }

  setOnExecutedEvent(callable: any): Action {
    this.onExecutedCallbacks.push(callable);
    return this;
  }

}