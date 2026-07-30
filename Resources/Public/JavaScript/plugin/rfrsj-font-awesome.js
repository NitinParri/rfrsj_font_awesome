import {Plugin} from "@ckeditor/ckeditor5-core";
import {ButtonView} from "@ckeditor/ckeditor5-ui";
import {default as modalObject} from "@typo3/backend/modal.js";

const faIcon = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640"><path fill="#9ebf43" d="M542.5,340l77.125-173.625c1.875-4.25,2.875-8.75,2.875-13.375,0-18.25-14.75-33-33-33H117.125c18.25-11.5,30.375-31.875,30.375-55C147.5,29.125,118.375,0,82.5,0S17.5,29.125,17.5,65c0,20.875,9.75,39.375,25,51.25v523.75h80v-80h467c18.25,0,33-14.75,33-33,0-4.625-1-9.125-2.875-13.375l-77.125-173.625ZM428.3742065,334.8125v-.2501221c-30.5,50.125-105.5,138.625061-203,87.625061-20.5,45-22.75,76.875061-22.75,76.875061l-21.5-3.5c8.625-30.75,23.75-57.875,37.625-78.25,1.125-2.125,10.75-18.625,35.25-35.25,0,0-19.5,6.875-36.625,27.375-4.249939-28.625-7.374939-114.875,99.250061-141.75,128.625-32.5,142.249939-86.75,142.249939-86.75,0,0,.75,102.75-30.5,153.875Z"/></svg>';

export class RfrsjFontAwesome extends Plugin {
    static pluginName = "RfrsjFontAwesome";

    init() {
        const editor = this.editor;

        this._config = editor.config.get("ui.RfrsjFontAwesome") ?? {};

        this._addFontawesomeCss(document);

        editor.ui.componentFactory.add(RfrsjFontAwesome.pluginName, () => {
            const button = new ButtonView();

            button.set({
                label: "Insert Font Awesome icon",
                withText: false,
                command: "ckeditor_fa",
                icon: faIcon,
            });

            button.on("execute", () => this._openElementBrowser(editor));

            return button;
        });
    }

    _openElementBrowser(editor) {
        const url = new URL("rfrsj-font-awesome-modal/modal.html", import.meta.url).pathname;
        const bc = new BroadcastChannel("fontawesome:selected");

        const modal = modalObject.advanced({
            type: modalObject.types.iframe,
            title: "Font Awesome icons",
            content: url,
            size: modalObject.sizes.large,
            callback: (modalEl) => {
                const iframe = modalEl.querySelector("iframe");

                iframe.addEventListener(
                    "load",
                    () => {
                        this._addFontawesomeCss(iframe.contentDocument);
                    },
                    {once: true},
                );

                bc.onmessage = (message) => {
                    this.insertIcon(message.data);
                    modalEl.hideModal();
                };
            },
        });

        modal.addEventListener("typo3-modal-hide", () => bc.close(), {once: true});
    }

    _addFontawesomeCss(doc) {
        const cssConfig = this._config?.css;
        if (!cssConfig) return;

        const head = doc.getElementsByTagName("head")[0];
        const existingHrefs = new Set(Array.from(head.querySelectorAll('link[rel="stylesheet"]')).map((link) => link.getAttribute("href")));

        Object.values(cssConfig).forEach((href) => {
            if (!href || existingHrefs.has(href)) return;

            const link = doc.createElement("link");
            link.rel = "stylesheet";
            link.type = "text/css";
            link.href = href;
            head.appendChild(link);
            existingHrefs.add(href);
        });
    }

    insertIcon(data) {
        const classes = Array.isArray(data?.classes) ? data.classes.join(" ") : (data ?? "");
        if (!String(classes).trim()) return;
        const iTag = '<i class="' + classes + '">&nbsp;</i>';
        const content = data.faList ? '<span class="fa-li">' + iTag + "</span> " : iTag + " ";
        const viewFragment = this.editor.data.processor.toView(content);
        const modelFragment = this.editor.data.toModel(viewFragment);
        this.editor.model.change(() => {
            this.editor.model.insertContent(modelFragment, this.editor.model.document.selection.getFirstPosition());
        });
    }
}
