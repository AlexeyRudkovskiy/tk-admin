import {AbstractAction} from "./abstract-action";

export class TableAddColumn extends AbstractAction {

  create() {
    this.anchor = document.createElement('a');
    this.anchor.innerHTML = 'додати комірку';
    this.anchor.href = 'javascript:';

    this.anchor.addEventListener('click', () => {
      const currentTdIndex = [...this.currentElement.parentElement.children].indexOf(this.currentElement);
      const table = this.currentElement.parentElement.parentElement.parentElement;
      const tbody = table.getElementsByTagName('tbody')[0];

      const th = document.createElement('th');
      this.insertAfter(th, this.currentElement);

      const trs = [...tbody.getElementsByTagName('tr')];
      for (let tr of trs) {
        const td = document.createElement('td');
        this.insertAfter(td, tr.children[currentTdIndex]);
        // console.log(td, tr, tr.children[currentTdIndex]);
        // tr.parentElement.insertBefore(td, tr.children[currentTdIndex]);
      }
    });
  }

  getIdentifier(): string {
    return "table.add_column";
  }

  getTargetElements(): string[] {
    return [ 'th' ];
  }

  private insertAfter(newElement: any, currentElement: any) {
    currentElement.parentNode.insertBefore(newElement, currentElement.nextSibling);
  }

}