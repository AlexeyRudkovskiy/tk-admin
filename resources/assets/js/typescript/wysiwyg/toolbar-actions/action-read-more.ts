import {AbstractAction} from "./abstract-action";

export class ActionReadMore extends AbstractAction {

  getDescription(): string {
    return "Додати \"Читати далі\"";
  }

  getIcon(): string {
    return "icon-article-alt";
  }

  getName(): string {
    return "toolbar.read_more";
  }

  performAction() {
    const readMoreParagraph = document.createElement('p');
    readMoreParagraph.innerHTML = '[read-more]';

    document.execCommand('insertHTML', false, readMoreParagraph.outerHTML);
  }

  prepare(): void {
  }

  createToolbarElement(): HTMLDivElement {
    return this.createSimpleToolbarElement();
  }

  shouldUpdateToolbarElement(current: HTMLElement): boolean {
    return false;
  }

  updateToolbarElement(): void {
    // empty
  }

}