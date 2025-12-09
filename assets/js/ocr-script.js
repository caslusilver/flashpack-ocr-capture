(function() {

    const video = document.getElementById("ocr-camera-video");
    const captureBtn = document.getElementById("ocr-capture-btn");
    const previewArea = document.getElementById("ocr-preview-area");
    const modalBG = document.getElementById("ocr-modal-bg");

    const webhook = window.FLASHPACK_WEBHOOK;

    const fNome = document.getElementById("ocr-field-nome");
    const fBloco = document.getElementById("ocr-field-bloco");
    const fAp = document.getElementById("ocr-field-ap");
    const fCep = document.getElementById("ocr-field-cep");
    const modalImg = document.getElementById("ocr-modal-img");
    const confirmBtn = document.getElementById("ocr-confirm-btn");

    let lastFullBase64 = null;
    let lastCropBase64 = null;

    async function startCamera() {
        const stream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: "environment" } });
        video.srcObject = stream;
    }
    startCamera();

    function captureFrame() {
        let canvas = document.createElement("canvas");
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        let ctx = canvas.getContext("2d");
        ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
        return canvas.toDataURL("image/jpeg");
    }

    function cropAddress(fullBase64) {
        return new Promise((resolve) => {
            let img = new Image();
            img.onload = function () {

                const videoRect = video.getBoundingClientRect();
                const addrRect = document.getElementById("ocr-frame-address").getBoundingClientRect();
                const scaleX = img.width / videoRect.width;
                const scaleY = img.height / videoRect.height;

                const cropX = (addrRect.left - videoRect.left) * scaleX;
                const cropY = (addrRect.top - videoRect.top) * scaleY;
                const cropW = addrRect.width * scaleX;
                const cropH = addrRect.height * scaleY;

                let canvas = document.createElement("canvas");
                canvas.width = cropW;
                canvas.height = cropH;
                let ctx = canvas.getContext("2d");
                ctx.drawImage(img, cropX, cropY, cropW, cropH, 0, 0, cropW, cropH);

                resolve(canvas.toDataURL("image/jpeg"));
            };
            img.src = fullBase64;
        });
    }

    function stripPrefix(b64) {
        return b64.replace(/^data:image\/[a-zA-Z]+;base64,/, "");
    }

    async function sendToWebhook(full, crop) {
        const payload = {
            image_full: stripPrefix(full),
            image_crop: stripPrefix(crop),
            timestamp: Date.now(),
            device: navigator.userAgent
        };

        const res = await fetch(webhook, {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(payload)
        });

        return res.json();
    }

    function parseComplemento(comp) {
        if (!comp) return { bloco: "", ap: "" };
        const nums = comp.match(/\d+/g) || [];
        return {
            bloco: nums[0] || "",
            ap: nums[1] || ""
        };
    }

    captureBtn.addEventListener("click", async () => {
        const full = captureFrame();
        const crop = await cropAddress(full);

        lastFullBase64 = full;
        lastCropBase64 = crop;

        previewArea.innerHTML = `
            <h4>Pré-visualização:</h4>
            <img src="${crop}" style="max-width:250px;border:2px solid red;border-radius:6px;">
        `;

        const response = await sendToWebhook(full, crop);

        const comp = parseComplemento(response.complemento);

        modalImg.src = crop;
        fNome.value = response.destinatario || "";
        fCep.value = response.cep || "";
        fBloco.value = comp.bloco;
        fAp.value = comp.ap;

        modalBG.style.display = "flex";
    });

    confirmBtn.addEventListener("click", async () => {
        const payload = {
            nome: fNome.value,
            bloco: fBloco.value,
            apartamento: fAp.value,
            cep: fCep.value,
            image_full: stripPrefix(lastFullBase64),
            image_crop: stripPrefix(lastCropBase64),
            timestamp: Date.now(),
            confirm: true
        };

        await fetch(webhook, {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(payload)
        });

        alert("✔ Dados salvos com sucesso!");
        modalBG.style.display = "none";
    });

})();
