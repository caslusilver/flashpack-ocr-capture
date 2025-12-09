<?php
$webhook = "https://webhook.cubensisstore.com.br/webhook/ronaldaoai";
?>

<div id="ocr-camera-wrapper" style="text-align:center; margin-top:20px; position:relative;">

    <h3>📦 Captura de Endereço para OCR</h3>

    <video id="ocr-camera-video" autoplay playsinline 
           style="width:100%; max-width:400px; border-radius:10px;">
    </video>

    <!-- Moldura Verde -->
    <div id="ocr-frame-big"
         style="
             position:absolute;
             border:3px solid #00ff95;
             width:80%;
             height:70%;
             top:15%;
             left:10%;
             pointer-events:none;
             border-radius:12px;
         ">
    </div>

    <!-- Moldura Vermelha CENTRALIZADA -->
    <div id="ocr-frame-address"
         style="
             position:absolute;
             border:3px solid red;
             width:70%;
             height:22%;
             top: calc(50% - 11%);
             left: calc(50% - 35%);
             pointer-events:none;
             border-radius:6px;
         ">
         <span style="
            color:red;
            font-size:12px;
            position:absolute;
            top:-18px;
            left:0;
         ">Posicione o endereço aqui</span>
    </div>

    <button id="ocr-capture-btn" 
            style="margin-top:20px; padding:12px 22px; font-size:16px; background:black; color:white; border:none; border-radius:8px;">
        📸 Capturar
    </button>

    <div id="ocr-preview-area" style="margin-top:25px;"></div>

</div>

<!-- MODAL -->
<div id="ocr-modal-bg">
    <div id="ocr-modal">
        <h3>Confirme os dados detectados</h3>

        <img id="ocr-modal-img" src="" style="width:100%;border-radius:8px;margin-bottom:12px;border:2px solid #e74c3c;">

        <label>Nome do Destinatário</label>
        <input id="ocr-field-nome" type="text" placeholder="Nome">

        <label>Bloco</label>
        <input id="ocr-field-bloco" type="text" placeholder="Ex: 11">

        <label>Apartamento</label>
        <input id="ocr-field-ap" type="text" placeholder="Ex: 34">

        <label>CEP</label>
        <input id="ocr-field-cep" type="text" placeholder="CEP">

        <button id="ocr-confirm-btn">Confirmar Recebimento</button>
    </div>
</div>

<script>
window.FLASHPACK_WEBHOOK = "<?php echo esc_js($webhook); ?>";
</script>
