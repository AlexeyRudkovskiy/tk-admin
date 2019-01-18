import {AbstractAction} from "./abstract-action";

export class ActionBold extends AbstractAction {

  protected isBold: boolean = false;

  getName(): string {
    return "toolbar.bold";
  }

  getDescription(): string {
    return "Жирний";
  }

  getIcon(): string {
    return "fa fa-bold";
  }

  prepare(): void {
    this.editor.addHotKey(this.composeHotKey('b'), this);
  }

  performAction() {
    document.execCommand('bold', false, null);
    // document.queryCommandState('bold');
  }

  shouldUpdateToolbarElement(current: HTMLElement): boolean {
    const isNowBold = document.queryCommandState('bold');
    const isShouldUpdate = isNowBold !== this.isBold;
    this.isBold = isNowBold;
    return isShouldUpdate;
  }

  updateToolbarElement(): void {
    this.element.classList.toggle('active');
  }

  createToolbarElement(): HTMLDivElement {
    return this.createSimpleToolbarElement();
  }

}