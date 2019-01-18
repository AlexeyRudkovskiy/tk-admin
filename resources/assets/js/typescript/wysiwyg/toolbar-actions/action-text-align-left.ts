import {AbstractAction} from "./abstract-action";
import {SimpleAction} from "./simple-action";

export class ActionTextAlignLeft extends SimpleAction {

  protected commandName: string = 'JustifyLeft';

  getDescription(): string {
    return "Вирівняти по лівому краю";
  }

  getIcon(): string {
    return "fa fa-align-left";
  }

  getName(): string {
    return "toolbar.text_align.left";
  }

}