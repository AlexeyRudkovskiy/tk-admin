import {SimpleAction} from "./simple-action";

export class ActionTextAlignJustify extends SimpleAction {

  protected commandName: string = 'JustifyFull';

  getDescription(): string {
    return "За шириною";
  }

  getIcon(): string {
    return "fa fa-align-justify";
  }

  getName(): string {
    return "toolbar.text_align.justify";
  }

}