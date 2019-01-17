import {SimpleAction} from "./simple-action";
import {AbstractAction} from "./abstract-action";

export class ActionTable extends AbstractAction {

  getDescription(): string {
    return "Додати таблицю";
  }

  getIcon(): string {
    return "fa fa-table";
  }

  getName(): string {
    return "toolbar.table";
  }

  createToolbarElement(): HTMLDivElement {
    return this.createSimpleToolbarElement();
  }

  performAction() {
    this.editor.restoreSelection();
    const range = getSelection().getRangeAt(0);
    let target = range.commonAncestorContainer as any;
    while (typeof target.tagName === "undefined" || target.tagName.toLowerCase() !== 'p') {
      target = target.parentElement;
    }

    const wrapper = document.createElement('div');
    const table = document.createElement('table');
    const thead = document.createElement('thead');
    const tbody = document.createElement('tbody');

    const theadTr = document.createElement('tr');
    const tbodyTr = document.createElement('tr');

    tbody.appendChild(tbodyTr);
    thead.appendChild(theadTr);

    table.appendChild(thead);
    table.appendChild(tbody);
    wrapper.appendChild(table);

    wrapper.classList.add('post-table');
    table.classList.add('table', 'table-stripe');

    table.setAttribute('border', '0');
    table.setAttribute('cellspacing', '0');
    table.setAttribute('cellpadding', '0');

    for (let i = 1; i <= 2; i++) {
      const td = document.createElement('td');
      const th = document.createElement('th');

      td.innerHTML = i.toString();
      th.innerHTML = i.toString();

      tbodyTr.appendChild(td);
      theadTr.appendChild(th);
    }

    const emptyP1 = document.createElement('p');
    const emptyP2 = document.createElement('p');

    target.parentElement.insertBefore(emptyP1, target.nextElementSibling);
    target.parentElement.insertBefore(wrapper, emptyP1);
    target.parentElement.insertBefore(emptyP2, wrapper);

  }

  prepare(): void {
    // empty
  }

  shouldUpdateToolbarElement(current: HTMLElement): boolean {
    return false;
  }

  updateToolbarElement(): void {
    // empty
  }

}