/*
 *  Mjpeg Player class
 *  Displays sequence of jpeg images on canvas HTML element.
 */
class MjpegPlayer {

    constructor() {
        this.img = new Image();
        this.img.referrerPolicy = "origin";
        this.running = false;
        this.frameCnt = 0;
        this.width = 0;
        this.height = 0;

        this.img.onload = () => {
            this.updateFrame();
        };
    }


    setSrc(src) {
        this.src = src;
    }


    scale(srcDim, dstDim) {
        var ratio = Math.min(dstDim.width / srcDim.width,
            dstDim.height / srcDim.height);
        var newDim = {
            x: 0,
            y: 0,
            width: srcDim.width * ratio,
            height: srcDim.height * ratio
        };
        newDim.x = (dstDim.width / 2) - (newDim.width / 2);
        newDim.y = (dstDim.height / 2) - (newDim.height / 2);
        return newDim;
    }


    setCanvas(canvas) {
        this.canvas = canvas;
        this.context = this.canvas.getContext("2d");
    }


    updateFrame() {
        this.canvas.width = this.img.naturalWidth;
        this.canvas.height = this.img.naturalHeight;
        var srcDim = {
            x: 0,
            y: 0,
            width: this.img.naturalWidth,
            height: this.img.naturalHeight
        };
        var dstDim = this.scale(srcDim, {
            width: this.canvas.width,
            height: this.canvas.height
        });

        try {
            this.context.drawImage(this.img,
                srcDim.x,
                srcDim.y,
                srcDim.width,
                srcDim.height,
                dstDim.x,
                dstDim.y,
                dstDim.width,
                dstDim.height
            );
            this.frameCnt++;
        } catch (e) {
            //this.stop();
            console.error("Canvas failed to draw image!");
            throw e;
        }
    }


    run() {
        try {
            this.frameTimer = setInterval(() => {
                if (this.img.complete) {
                    this.img.src = `${this.src}?dummy=` + new Date().getTime();
                }
            }, 20);
        } catch (err) {
            console.error(`${err.name}: ${err.message}`);
            //console.trace();
        }
        this.running = true;
    }


    stop() {
        this.img.src = "#";
        try {
            clearInterval(this.frameTimer);
        } catch (err) {
            console.error(`${err.name}: ${err.message}`);
            //console.trace();
        }
        this.running = false;
    }

    isRunning() {
        return this.running;
    }

    getWidth() {
        return this.img.naturalWidth;
    }

    getHeight() {
        return this.img.naturalHeight;
    }
}


export default MjpegPlayer;
