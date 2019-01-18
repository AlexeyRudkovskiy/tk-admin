import {AbstractAction} from "./abstract-action";

export class ActionItalic extends AbstractAction {

  protected isItalic: boolean = false;

  getDescription(): string {
    return "Курсив";
  }

  getIcon(): string {
    return "fa fa-italic";
  }

  getName(): string {
    return "toolbar.italic";
  }

  performAction() {
    document.execCommand('italic', false, null);
  }

  prepare(): void {
    this.editor.addHotKey(this.composeHotKey('i'), this);
  }

  shouldUpdateToolbarElement(current: HTMLElement): boolean {
    const isItalicNow = document.queryCommandState('italic');
    const shouldUpdate = this.isItalic !== isItalicNow;
    this.isItalic = isItalicNow;

    return shouldUpdate;
  }

  updateToolbarElement(): void {
    this.element.classList.toggle('active');
  }

  createToolbarElement(): HTMLDivElement {
    return this.createSimpleToolbarElement();
  }

}