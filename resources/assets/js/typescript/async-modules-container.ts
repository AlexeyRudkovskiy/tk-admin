export class AsyncModulesContainer {

  private static _instance: AsyncModulesContainer = null;

  private modules: any = {};

  private constructor() {  }

  public static get instance() {
    if (this._instance === null) {
      this._instance = new AsyncModulesContainer();
    }

    return this._instance;
  }

  public addModule(tag: string, action: any) {
    if (!this.modules.hasOwnProperty(tag)) {
      this.modules[tag] = [];
    }

    this.modules[tag].push(action);
  }

  public runModules(tag: string) {
    if (this.modules.hasOwnProperty(tag)) {
      this.modules[tag]
        .forEach(module => module.call(window));
    }
  }

}