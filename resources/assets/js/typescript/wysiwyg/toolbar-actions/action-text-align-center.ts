import {SimpleAction} from "./simple-action";

export class ActionTextAlignCenter extends SimpleAction {

  protected commandName: string = 'JustifyCenter';

  getDescription(): string {
    return "Центрувати";
  }

  getIcon(): string {
    return "fa fa-align-center";
  }

  getName(): string {
    return "toolbar.text_align.center";
  }

}