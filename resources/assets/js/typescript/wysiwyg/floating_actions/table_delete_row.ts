import {AbstractAction} from "./abstract-action";

export class TableDeleteRow extends AbstractAction {

  create() {
    this.anchor = document.createElement('a');
    this.anchor.innerHTML = 'видалити рядок';
    this.anchor.setAttribute('href', 'javascript:');
    this.anchor.addEventListener('click', () => {
      const tr = this.currentElement.parentElement;
      const table = tr.parentElement;
      table.removeChild(tr);

      this.editor.hideFloatingActionPanel();
    });
  }

  getIdentifier(): string {
    return "table.delete_row";
  }

  getTargetElements(): string[] {
    return [ 'td', 'th' ];
  }

}