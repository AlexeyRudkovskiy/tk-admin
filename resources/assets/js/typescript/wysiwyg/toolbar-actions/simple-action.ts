import {AbstractAction} from "./abstract-action";

export abstract class SimpleAction extends AbstractAction {

  protected commandName: string = null;

  protected commandValue: string = null;

  protected hotKey: string = null;

  protected isActive: boolean = false;

  createToolbarElement(): HTMLDivElement {
    return this.createSimpleToolbarElement();
  }

  abstract getDescription(): string;

  abstract getIcon(): string;

  abstract getName(): string;

  performAction() {
    document.execCommand(this.commandName, false, this.commandValue);
  }

  prepare(): void {
    this.editor.addHotKey(this.hotKey, this);
  }

  shouldUpdateToolbarElement(current: HTMLElement): boolean {
    const isActiveNow = document.queryCommandState(this.commandName);
    const shouldUpdate = this.isActive !== isActiveNow;
    this.isActive = isActiveNow;
    return shouldUpdate;
  }

  updateToolbarElement(): void {
    this.element.classList.toggle('active');
  }

}