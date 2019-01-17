import {AbstractAction} from "./abstract-action";

export class ActionUnderline extends AbstractAction {

  protected isUnderline: boolean = false;

  createToolbarElement(): HTMLDivElement {
    return this.createSimpleToolbarElement();
  }

  getDescription(): string {
    return "Підкреслити";
  }

  getIcon(): string {
    return "fa fa-underline";
  }

  getName(): string {
    return "toolbar.underline";
  }

  performAction() {
    document.execCommand('underline');
  }

  prepare(): void {
    this.editor.addHotKey(this.composeHotKey('u'), this);
  }

  shouldUpdateToolbarElement(current: HTMLElement): boolean {
    const isUnderlineNow = document.queryCommandState('underline');
    const shouldToggle = isUnderlineNow !== this.isUnderline;
    this.isUnderline = isUnderlineNow;
    return shouldToggle;
  }

  updateToolbarElement(): void {
    this.element.classList.toggle('active');
  }

}