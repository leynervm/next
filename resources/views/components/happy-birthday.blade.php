<div>
    <div id="form_happy" style="display: none;"
        class="fixed top-0 left-0 w-screen h-screen z-[9999] bg-[#131416] opacity-80 overflow-hidden">

        <h1 id="name_hb"
            class="w-full text-center text-3xl sm:text-5xl absolute top-[50%] left-[50%] -translate-x-[50%] -translate-y-[50%] text-white font-semibold">
            FELIZ CUMPLEAÑOS LAUTARO</h1>

        <canvas id="birthday" class="block"></canvas>
        <div class="absolute top-[calc(50%--125px)] left-[50%] -translate-x-[50%]">
            <x-button id="testbutton" class="bg-transparent ring-2 ring-white p-3 px-5" @click="this.happy= false">ACEPTAR</x-button>
        </div>

        {{-- <div id="testbutton" @click="this.happy= false"></div> --}}
    </div>

    <script>
        let name_happy_birthday = document.getElementById('name_hb');
        let form_happy = document.getElementById('form_happy');
        let button_close_happy = document.getElementById('testbutton');

        window.addEventListener('birthday', data => {
            form_happy.style.display = 'block';
            name_happy_birthday.innerHTML = '<small>FELIZ CUMPLEAÑOS</small> </br>' + data.detail;
        });

        button_close_happy.addEventListener('click', function(e) {
            form_happy.style.display = 'none';
        })


        //01000001 01010011
        // helper functions
        const PI2 = Math.PI * 2
        const random = (min, max) => Math.random() * (max - min + 1) + min | 0
        const timestamp = _ => new Date().getTime()

        class Birthday {
            constructor() {
                this.resize()

                // create a lovely place to store the firework
                this.fireworks = []
                this.counter = 0

            }

            resize() {
                this.width = canvas.width = window.innerWidth
                let center = this.width / 2 | 0
                this.spawnA = center - center / 4 | 0
                this.spawnB = center + center / 4 | 0

                this.height = canvas.height = window.innerHeight
                this.spawnC = this.height * .1
                this.spawnD = this.height * .5

            }

            onClick(evt) {
                let x = evt.clientX || evt.touches && evt.touches[0].pageX
                let y = evt.clientY || evt.touches && evt.touches[0].pageY

                let count = random(3, 10)
                for (let i = 0; i < count; i++) this.fireworks.push(new Firework(
                    random(this.spawnA, this.spawnB),
                    this.height,
                    x,
                    y,
                    random(0, 260),
                    random(30, 110)))

                this.counter = -1

            }

            update(delta) {
                ctxhb.globalCompositeOperation = 'hard-light'
                ctxhb.fillStyle = `rgba(20,20,20,${ 7 * delta })`
                ctxhb.fillRect(0, 0, this.width, this.height)

                ctxhb.globalCompositeOperation = 'lighter'
                for (let firework of this.fireworks) firework.update(delta)

                // if enough time passed... create new new firework
                this.counter += delta * 3 // each second
                if (this.counter >= 1) {
                    this.fireworks.push(new Firework(
                        random(this.spawnA, this.spawnB),
                        this.height,
                        random(0, this.width),
                        random(this.spawnC, this.spawnD),
                        random(0, 360),
                        random(30, 110)))
                    this.counter = 0
                }

                // remove the dead fireworks
                if (this.fireworks.length > 1000) this.fireworks = this.fireworks.filter(firework => !firework.dead)

            }
        }

        class Firework {
            constructor(x, y, targetX, targetY, shade, offsprings) {
                this.dead = false
                this.offsprings = offsprings

                this.x = x
                this.y = y
                this.targetX = targetX
                this.targetY = targetY

                this.shade = shade
                this.history = []
            }
            update(delta) {
                if (this.dead) return

                let xDiff = this.targetX - this.x
                let yDiff = this.targetY - this.y
                if (Math.abs(xDiff) > 3 || Math.abs(yDiff) > 3) { // is still moving
                    this.x += xDiff * 2 * delta
                    this.y += yDiff * 2 * delta

                    this.history.push({
                        x: this.x,
                        y: this.y
                    })

                    if (this.history.length > 20) this.history.shift()

                } else {
                    if (this.offsprings && !this.madeChilds) {

                        let babies = this.offsprings / 2
                        for (let i = 0; i < babies; i++) {
                            let targetX = this.x + this.offsprings * Math.cos(PI2 * i / babies) | 0
                            let targetY = this.y + this.offsprings * Math.sin(PI2 * i / babies) | 0

                            birthday.fireworks.push(new Firework(this.x, this.y, targetX, targetY, this.shade, 0))

                        }

                    }
                    this.madeChilds = true
                    this.history.shift()
                }

                if (this.history.length === 0) this.dead = true
                else if (this.offsprings) {
                    for (let i = 0; this.history.length > i; i++) {
                        let point = this.history[i]
                        ctxhb.beginPath()
                        ctxhb.fillStyle = 'hsl(' + this.shade + ',100%,' + i + '%)'
                        ctxhb.arc(point.x, point.y, 1, 0, PI2, false)
                        ctxhb.fill()
                    }
                } else {
                    ctxhb.beginPath()
                    ctxhb.fillStyle = 'hsl(' + this.shade + ',100%,50%)'
                    ctxhb.arc(this.x, this.y, 1, 0, PI2, false)
                    ctxhb.fill()
                }

            }
        }

        let canvas = document.getElementById('birthday')
        let ctxhb = canvas.getContext('2d')
        //01000001 01010011
        let then = timestamp()

        let birthday = new Birthday
        window.onresize = () => birthday.resize()
        document.onclick = evt => birthday.onClick(evt)
        document.ontouchstart = evt => birthday.onClick(evt);
        (function loop() {
            requestAnimationFrame(loop)

            let now = timestamp()
            let delta = now - then

            then = now
            birthday.update(delta / 1000)


        })()
    </script>
</div>
