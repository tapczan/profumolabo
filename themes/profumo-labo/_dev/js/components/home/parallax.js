/**
 * Parallax effect above offerta
 * @param {int} canvasFrameCount - the value of how many sequence of images you will be placing for the effect
 * @param {int} jsCanvasSectionOffset - get canvas section offset top
 * @param {int} jsCanvasStart - get the vertical scroll position of the canvas that will serve as the starting point value of the canvas scroll animation
 * @param {int} jsCanvasMaximum - get the end (or maximum) value that will serve as the end point value of the canvas scroll animation
 * @param {int} jsCanvasProgress - get users scroll progress
 * @param {int} jsCanvasBottomValue - get canvas section wrapper distance to the top of the browser which will give some bottom value for the canvas element
 * @param {int} canvasImgWidth - width of the actual picture for responsive compatibility
 * @param {int} canvasImgHeight - height of the actual picture for responsive compatibility
 */
const canvas = $("#js-canvas-offerta");

if(canvas.length){
    const canvasContext = canvas[0].getContext('2d');
    const canvasFrameCount = 39;
    const winLocationOrigin = window.location.origin;

    const canvasCurrentFrame = index => (
        `${winLocationOrigin}/img/cms/parallax/canvas-offerta-${index.toString().padStart(3, '0')}.jpg`
    );

    const preloadCanvasImage = () => {
        for (let i = 1; i < canvasFrameCount; i++) {
            const canvasImgPreload = new Image();
            canvasImgPreload.src = canvasCurrentFrame(i);
        }
    };

    var canvasImg = new Image();
    var canvasImgWidth = 3634;
    var canvasImgHeight = 1692;
        
    function initCanvas() {
        canvasContext.canvas.width = canvasImgWidth;
        canvasContext.canvas.height = canvasImgHeight;

        drawCanvas(); 
    }
        
    function drawCanvas() {
        canvasImg.src = canvasCurrentFrame(1);
    }

    canvasImg.onload=function(){
        canvasContext.drawImage(canvasImg, 0, 0);
    }

    const updateImage = index => {
        canvasImg.src = canvasCurrentFrame(index);
        canvasContext.drawImage(canvasImg, 0, 0);
    }
    
    initCanvas();

    preloadCanvasImage();

    $(window).on('scroll', function() {
        const jsAfterCanvasSection = $(".blockhomesections--profumo");
        const jsAfterCanvasSectionOffset = jsAfterCanvasSection.offset().top;
        const jsCanvasSection = $(".js-canvas-parallax");
        const jsCanvasSectionOffset = jsCanvasSection.offset().top;
        const jsCanvasStart = $(this).scrollTop() + $(this).height();
        const jsCanvasMaximum = jsCanvasStart - jsCanvasSectionOffset;
        const jsCanvasProgress = jsCanvasMaximum / (jsCanvasSection.height() * 2);
        const jsCanvasElement = $('#js-canvas-offerta');  
        const jsCanvasSectionNative = document.getElementsByClassName("js-canvas-parallax")[0];

        if (jsCanvasStart >= jsCanvasSectionOffset) {
            const frameIndex = Math.min(
                canvasFrameCount - 1,
                Math.ceil(jsCanvasProgress * canvasFrameCount)
            );

            requestAnimationFrame(() => updateImage(frameIndex + 1));
            jsCanvasElement.css('position', 'fixed');
        }

        if (jsCanvasStart >= jsAfterCanvasSectionOffset) {
            jsCanvasElement.css('position', 'absolute');
        }
    });
}