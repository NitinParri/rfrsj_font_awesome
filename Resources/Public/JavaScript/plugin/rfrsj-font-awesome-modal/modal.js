const iconGroups = {faIcons: [], faBrands: []};

async function loadIconGroups() {
    const [icons, brands] = await Promise.all([fetch("./icons.json").then((response) => response.json()), fetch("./brands.json").then((response) => response.json())]);

    iconGroups.faIcons = icons;
    iconGroups.faBrands = brands;
}

(function () {
    const catMap = {
        brands: {prefix: "fa-brands", hasWeight: false, iconsGroup: "faBrands"},
        classic: {prefix: "", hasWeight: true, iconsGroup: "faIcons"},
        sharp: {prefix: "fa-sharp", hasWeight: true, iconsGroup: "faIcons"},
        duotone: {prefix: "fa-duotone", hasWeight: true, iconsGroup: "faIcons"},
        sharpduotone: {prefix: "fa-sharp-duotone", hasWeight: true, iconsGroup: "faIcons"},
    };

    let activeCat = null;
    let activeWt = null;
    const iconSection = document.querySelector("#faIcons");
    const catBtns = document.querySelectorAll("#faCatGrp button[data-cat]");
    const wtBtns = document.querySelectorAll("#faWtGrp button[data-wt]");
    const searchInput = document.querySelector('input[name="search"]');

    function buildPrefix() {
        if (!activeCat) return null;
        const def = catMap[activeCat];
        if (!def.hasWeight) return "fa-brands";
        if (!activeWt) return null;
        const parts = [activeWt];
        if (def.prefix) parts.push(def.prefix);
        return parts.join(" ");
    }

    function applySearch() {
        const val = searchInput.value.toLowerCase();
        iconSection.childNodes.forEach(function (item) {
            const icon = item.childNodes[0] && item.childNodes[0].dataset["icon"];
            item.style.display = !val || (icon && icon.indexOf(val) >= 0) ? "" : "none";
        });
    }

    function triggerIcons() {
        const prefix = buildPrefix();
        if (!prefix) return;
        faRender(iconGroups[catMap[activeCat].iconsGroup], prefix);
        applySearch();
    }

    function syncWeightBtns() {
        const hasWeight = catMap[activeCat].hasWeight;
        wtBtns.forEach(function (b) {
            b.disabled = !hasWeight;
            if (!hasWeight) {
                b.classList.remove("active", "btn-light");
                b.classList.add("btn-outline-secondary");
            }
        });
        if (!hasWeight) activeWt = null;
    }

    catBtns.forEach(function (btn) {
        btn.addEventListener("click", function () {
            catBtns.forEach(function (b) {
                b.classList.remove("active", "btn-light");
                b.classList.add("btn-dark");
            });
            btn.classList.add("active", "btn-light");
            btn.classList.remove("btn-dark");
            activeCat = btn.dataset.cat;
            syncWeightBtns();

            if (catMap[activeCat].hasWeight && !activeWt) {
                document.querySelector('[data-wt="fa-solid"]').click();
                return;
            }

            triggerIcons();
        });
    });

    wtBtns.forEach(function (btn) {
        btn.addEventListener("click", function () {
            if (btn.disabled) return;
            wtBtns.forEach(function (b) {
                b.classList.remove("active", "btn-light");
                b.classList.add("btn-outline-secondary");
            });
            btn.classList.add("active", "btn-light");
            btn.classList.remove("btn-outline-secondary");
            activeWt = btn.dataset.wt;
            triggerIcons();
        });
    });

    function faRender(fas, ty) {
        let icons = "";
        for (const ids of fas) {
            icons += '<div class="col">' + '<a href="#" class="p-2" onclick="return false;" data-icon="' + ty + " fa-" + ids + '">' + '<i class="' + ty + " fa-" + ids + '"></i>' + ids.split("-").join(" ") + "</a></div>";
        }
        iconSection.innerHTML = icons;
        iconSection.querySelectorAll("a").forEach(function (link) {
            link.addEventListener("click", klick);
        });
    }

    searchInput.addEventListener("keyup", applySearch);

    function klick(e) {
        const icon = e.currentTarget.dataset["icon"];
        const selectedClass = icon ? [icon] : [];
        const faSize = document.querySelector('[name="faSize"]').value || null;
        const faAnimate = document.querySelector('[name="faAnimate"]').value || null;
        const faRotate = document.querySelector('[name="faRotate"]').value || null;
        const faPull = document.querySelector('[name="faPull"]').value || null;
        const faWidthAuto = document.querySelector('[name="faWidthAuto"]').checked;
        const faList = document.querySelector('[name="faList"]').checked;
        if (faSize) selectedClass.push(faSize);
        if (faAnimate) selectedClass.push(faAnimate);
        if (faRotate) selectedClass.push(faRotate);
        if (faPull) selectedClass.push(faPull);
        if (faWidthAuto) selectedClass.push("fa-width-auto");
        const bc = new BroadcastChannel("fontawesome:selected");
        bc.postMessage({classes: selectedClass, faList});
        bc.close();
    }

    loadIconGroups()
        .then(function () {
            document.querySelector('[data-cat="classic"]').click();
            document.querySelector('[data-wt="fa-solid"]').click();
        })
        .catch(function (error) {
            iconSection.innerHTML = '<div class="col-12 text-center text-danger p-3">Kon de icon-lijsten niet laden: ' + error.message + "</div>";
        });
})();
