import {SimpleAction} from "./simple-action";

export class ActionListOrdered extends SimpleAction {

  protected commandName: string = 'insertOrderedList';

  protected hotKey: string = this.composeHotKey('shift+o');

  getDescription(): string {
    return "Нумерований список";
  }

  getIcon(): string {
    return "fa fa-list-ol";
  }

  getName(): string {
    return "toolbar.list.ordered";
  }

  performAction(): void {
    super.performAction();

    document.execCommand('formatBlock', false, 'p');
  }
}