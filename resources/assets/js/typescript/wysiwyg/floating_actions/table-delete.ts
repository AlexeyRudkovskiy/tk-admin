import {AbstractAction} from "./abstract-action";

export class TableDelete extends AbstractAction {

  create() {
    this.anchor = document.createElement('a');
    this.anchor.innerHTML = 'видалити таблицю';
    this.anchor.setAttribute('href', 'javascript:');
    this.anchor.addEventListener('click', () => {
      let tableElement = this.currentElement;
      while (tableElement.tagName.toLowerCase() !== 'table') {
        tableElement = tableElement.parentElement;
      }

      const tableWrapper = tableElement.parentElement.parentElement;
      tableWrapper.parentElement.removeChild(tableWrapper);

      this.editor.hideFloatingActionPanel();
    });
  }

  getIdentifier(): string {
    return "table.delete";
  }

  getTargetElements(): string[] {
    return [ 'table', 'td', 'th' ];
  }

}