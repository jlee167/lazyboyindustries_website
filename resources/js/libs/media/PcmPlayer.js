/**
 *  Javascript based PCM player.
 *  Current Setting assumes 24bit PCM data in 32bit
 */

class PcmPlayer {

    #audioCtx;
    #audioBuffer;
    #audioSource;
    #startTime;
    #samples;
    #sampleRate;

    #refreshTime;
    #workerProcess;

    #running;


    constructor(options) {
        this.#audioCtx = new (window.AudioContext || window.webkitAudioContext)();
        this.#audioBuffer = this.#audioCtx.createBuffer(
            1,
            1024,
            options.sampleRate
        );
        this.#sampleRate = options.sampleRate;
        this.#refreshTime = options.refreshTime;
        this.#samples = new Float32Array();
        this.#startTime = this.#audioCtx.currentTime;
        this.#running = false;
    }


    process() {
        if (!this.#running) {return;}
        if (this.#samples.length < (2*this.#sampleRate)) {return;}

        //let audioLength = this.#samples.length;
        //let channelData = this.#audioBuffer.getChannelData(0);

        this.#audioBuffer.copyToChannel(this.#samples, 0);
        this.#audioSource = this.#audioCtx.createBufferSource();
        this.#audioSource.buffer = this.#audioBuffer;
        this.#audioSource.connect(this.#audioCtx.destination);

        console.log(this.#audioBuffer.duration);

        this.#audioSource.start(
            this.#audioCtx.currentTime,//this.#startTime,
            0,
            this.#audioBuffer.duration
        );

        this.#startTime += this.#audioBuffer.duration;
        this.#samples = new Float32Array();
        console.log(`Current:${this.#audioCtx.currentTime} \n
            Start Time: ${this.#startTime}`);
    }


    feed(data) {
        //if (data.byteLength % 4) {
        //    throw Error("Bytes to float conversion failed: byte count is not multiple of 4.");
        //}

        /* Normalize 24 Bit PCM into [-1.0, 1.0] range floating number. */
        let newSamples = new Float32Array(this.#samples.length + data.length);
        newSamples.set(this.#samples, 0);

        const offset = this.#samples.length;
        for (let i = 0; i < data.length; i++) {
            newSamples[offset + i] = data[i]/2147483648;
        }

        this.#samples = newSamples;
    }


    play() {
        this.#running = true;
        this.#workerProcess = setInterval(() => {
            this.process();
        }, this.#refreshTime);
    }


    stop() {
        if (!this.#workerProcess) {
            throw Error("Player is not running.");
        }

        this.#running = false;
        clearInterval(this.#workerProcess);
        this.#workerProcess = null;
    }

    get running() {
        return this.#running;
    }

    set running(value) {
        return new Error(`This variable is read only`);
    }
}


export default PcmPlayer;
