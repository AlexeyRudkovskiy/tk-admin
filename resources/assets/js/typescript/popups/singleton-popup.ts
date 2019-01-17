import {Popup} from "../popup";

export class SingletonPopup extends Popup {

  protected static instances: any = {};

  protected constructor(create?:boolean) { super(create); }

  public static getInstance<T extends Popup>(c: new () => T, ...args): T {
    const tag = c.name;
    if (!this.instances.hasOwnProperty(tag)) {
      this.instances[tag] = new c();
    }
    return this.instances[tag];
  }

}