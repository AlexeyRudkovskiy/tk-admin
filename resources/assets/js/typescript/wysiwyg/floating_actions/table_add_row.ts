import {FloatingAction} from "./floating_action";

export class TableAddRowAction implements FloatingAction {

  private anchor: HTMLAnchorElement;

  private element: any;

  private editor: any;

  create() {
    this.anchor = document.createElement('a');
    this.anchor.innerHTML = 'додати рядок';
    this.anchor.href = 'javascript:';

    this.anchor.addEventListener('click', () => {
      const currentTr = this.element.parentElement;
      const tdsCount = currentTr.childElementCount;

      const newTr = document.createElement('tr');
      for (let i = 0; i < tdsCount; i++) {
        const newTd = document.createElement('td');
        newTr.appendChild(newTd);
      }

      currentTr.parentNode.insertBefore(newTr, currentTr.nextSibling);
    });
  }

  getIdentifier(): string {
    return "table.add_row";
  }

  getAnchor(): HTMLAnchorElement {
    return this.anchor;
  }

  getTargetElements(): string[] {
    return [ 'td', 'th' ];
  }

  setCurrentElement(element) {
    this.element = element;
  }

  setEditor(editor: any) {
    this.editor = editor;
  }

  shouldBeDisplayed(): boolean {
    return false;
  }

}