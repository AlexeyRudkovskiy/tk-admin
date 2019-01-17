import {WYSIWYGField} from './wysiwyg/field'
import modules from './modules'
import {AsyncModulesContainer} from "./async-modules-container";
import Translation from "./modules/translation";
import Vue from 'vue'
import _ from 'lodash'
import axios from 'axios';

import AddMenuItem from './vue-components/AddMenuItem.vue'
import MenuField from './vue-components/MenuField.vue'
import MenuBuilder from './vue-components/MenuBuilder.vue'
import FileManager from './vue-components/file-manager/FileManager.vue'

export const eventsBus = new Vue();

(function () {

  const fields = [
    new WYSIWYGField()
  ];
  const asyncModulesContainer: AsyncModulesContainer = AsyncModulesContainer.instance;
  const csrfToken = (document.querySelector('meta[name="csrf_token"]') as HTMLMetaElement).content;
  (window as any)._csrf_token = csrfToken;

  for (let field of fields) {
    field.render();
  }

  let token: HTMLMetaElement = document.querySelector('meta[name="csrf_token"]') as HTMLMetaElement;

  (window as any)._ = _;
  (window as any).axios = axios;
  (window as any).axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;

  modules.onetime
    .forEach(module => module.call(window));

  if (typeof modules.async !== "undefined") {

    for (let tag in modules.async) {
      if (tag === 'any') {
        continue;
      }

      for (let module of modules.async[tag]) {
        asyncModulesContainer.addModule(tag, module);
      }
    }

    if (typeof modules.async.any !== "undefined") {
      const asyncModules = Object.keys(modules.async)
        .filter(module => module !== 'any');

      for (let tag of asyncModules) {
        for (let module of modules.async.any) {
          asyncModulesContainer.addModule(tag, module);
        }
      }
    }

  }

  asyncModulesContainer.runModules('any');

  (window as any).setTimeout(() => asyncModulesContainer.runModules('file'), 1);

  // const shouldBeTranslated = [...document.querySelectorAll('[data-translate]')];
  // const translation: Translation = Translation.getInstance();
  // shouldBeTranslated
  //   .forEach(element => element.innerHTML = translation.translate(element.getAttribute('data-translate')));

  (Vue as any).component('AddMenuItem', AddMenuItem);
  (Vue as any).component('MenuField', MenuField);
  (Vue as any).component('MenuBuilder', MenuBuilder);
  (Vue as any).component('FileManager', FileManager);

  const vueApps = document.querySelectorAll('.vue-app');
  for (let i = 0; i < vueApps.length; i++) {
    const vueApp = new Vue({
      el: vueApps[i]
    });
  }

  // const addMenuLink = document.querySelector('[data-add-menu-link]');
  // const menuField = document.querySelector('[data-menu-vue-app]');
  //
  // if (addMenuLink !== null && menuField !== null) {
  //   let menuObject: any = (window as any).menus[menuField.getAttribute('data-id')];;
  //
  //   if (menuObject === null) {
  //     menuObject = [];
  //   }
  //
  //   if (addMenuLink !== null) {
  //     new Vue({
  //       render: h => h(AddMenuItem, {
  //         props: {
  //           id: addMenuLink.getAttribute('data-id'),
  //           entities: menuObject.entities
  //         }
  //       }),
  //     }).$mount('[data-add-menu-link]')
  //   }
  //
  //   if (menuField !== null) {
  //     new Vue({
  //       render: h => h(MenuField, {
  //         props: {
  //           id: menuField.getAttribute('data-id'),
  //           fieldName: menuField.getAttribute('data-name'),
  //           items: menuObject.items
  //         }
  //       }),
  //     }).$mount('[data-menu-vue-app]')
  //   }
  // }

})();
