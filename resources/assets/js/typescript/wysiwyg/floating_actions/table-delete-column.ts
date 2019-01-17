import {AbstractAction} from "./abstract-action";

export class TableDeleteColumn extends AbstractAction {

  create() {
    this.anchor = document.createElement('a');
    this.anchor.href = 'javascript:';
    this.anchor.innerHTML = 'видалити комірку';
    this.anchor.addEventListener('click', () => {
      const column = this.currentElement;
      const columnIndex = [...column.parentElement.childNodes].indexOf(column);
      const rows = column.parentElement.parentElement.parentElement.querySelectorAll('tr');
      for (let i = 0; i < rows.length; i++) {
        const _childElement = rows[i].childNodes[columnIndex];
        _childElement.parentElement.removeChild(_childElement);
      }
    });
  }

  getIdentifier(): string {
    return "table.delete_column";
  }

  getTargetElements(): string[] {
    return ['th'];
  }

}