import {FloatingAction} from "./floating_action";

export abstract class AbstractAction implements FloatingAction {

  protected anchor: HTMLAnchorElement;

  protected currentElement: any;

  protected editor: any;

  abstract create();

  abstract getIdentifier(): string;

  abstract getTargetElements(): string[];

  getAnchor(): HTMLAnchorElement {
    return this.anchor;
  }

  setCurrentElement(element) {
    this.currentElement = element;
  }

  setEditor(editor: any) {
    this.editor = editor;
  }

  protected createDefaultAnchor(text: string, href: string = 'javascript:') {
    this.anchor = document.createElement('a');
    this.anchor.href = 'javascript:';
    this.anchor.innerHTML = text;
    return this.anchor;
  }

  shouldBeDisplayed(element: HTMLElement): boolean {
    return false;
  }

}