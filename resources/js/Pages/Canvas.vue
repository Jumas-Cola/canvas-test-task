<script setup>
import { Head, Link } from '@inertiajs/inertia-vue3';

defineProps({
  canLogin: Boolean,
  canRegister: Boolean,
  laravelVersion: String,
  phpVersion: String,
});
</script>

<template>
  <Head title="Canvas" />

  <div
    class="relative flex items-top justify-center min-h-screen bg-gray-300 sm:items-center sm:pt-0"
  >
    <div v-if="canLogin" class="hidden fixed top-0 right-0 px-6 py-4 sm:block">
      <Link
        v-if="$page.props.auth.user"
        :href="route('dashboard')"
        class="text-sm text-gray-700 underline"
      >
        Dashboard
      </Link>

      <template v-else>
        <Link :href="route('login')" class="text-sm text-gray-700 underline">
          Log in
        </Link>

        <Link
          v-if="canRegister"
          :href="route('register')"
          class="ml-4 text-sm text-gray-700 underline"
        >
          Register
        </Link>
      </template>
    </div>

    <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
      <div id="app">
        <h2 class="text-lg">Рисовалка</h2>
        <div class="main">
          <div class="color-guide">
            <h5 class="text-lg">Размер кисти:</h5>
            <input
              type="range"
              min="1"
              max="50"
              value="5"
              class="slider"
              id="sizeRange"
              @change="handleLineWidthSlide"
            />
            <h5 class="text-lg">Цвет кисти:</h5>
            <select name="colors" id="colorSelect" @change="handleColorChange">
              <option value="#000">Чёрный</option>
              <option value="#ef2110">Красный</option>
              <option value="#e67919">Оранжевый</option>
              <option value="#ffcc00">Желтый</option>
              <option value="#08f735">Зелёный</option>
              <option value="#3aebca">Голубой</option>
              <option value="#011afe">Синий</option>
              <option value="#b24ab5">Фиолетовый</option>
              <option value="#fff">Ластик</option>
            </select>
            <h5 class="text-lg">Вставка картинки:</h5>
            <input
              type="file"
              accept="image/*"
              @change="uploadImage"
              id="file-input"
            />
            <img :src="image" alt="" />
            <button
              v-if="image"
              type="button"
              @click="setInsertImage"
              class="btn"
            >
              Вставить
            </button>
            <h5 class="text-lg">Сохранить:</h5>
            <button type="button" class="btn" @click="sendToGallery">
              Сохранить
            </button>
          </div>
          <canvas ref="canvas"></canvas>
        </div>

        <h2 class="text-lg mt-4">Галерея</h2>
        <div class="gallery">
          <div class="img-card"></div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      color: '#000',
      image: '',
      isInsertImage: false,
    };
  },
  mounted() {
    const canvas = this.$refs.canvas;
    const ctx = canvas.getContext('2d');

    canvas.width = 800;
    canvas.height = 600;

    ctx.lineJoin = 'round';
    ctx.lineCap = 'round';
    ctx.lineWidth = 5;

    ctx.beginPath();
    ctx.rect(0, 0, canvas.width, canvas.height);
    ctx.fillStyle = '#fff';
    ctx.fill();

    this.prevPos = { offsetX: 0, offsetY: 0 };
    this.line = [];
    this.isPainting = false;

    canvas.addEventListener('mousedown', this.handleMouseDown);
    canvas.addEventListener('mousemove', this.handleMouseMove);
    canvas.addEventListener('mouseup', this.endPaintEvent);
    canvas.addEventListener('mouseleave', this.endPaintEvent);

    this.canvas = canvas;
    this.ctx = ctx;
  },
  methods: {
    sendToGallery() {
      var dataURL = this.canvas.toDataURL();
      console.log(dataURL);
    },
    setInsertImage() {
      this.isInsertImage = true;
    },
    insertImage(x, y) {
      let base_image = new Image();
      base_image.src = this.image;
      base_image.onload = () => {
        this.ctx.drawImage(base_image, x, y);
      };
    },
    uploadImage(e) {
      this.image = URL.createObjectURL(event.target.files[0]);
    },
    handleColorChange(e) {
      this.color = e.target.value;
    },
    handleLineWidthSlide(e) {
      this.ctx.lineWidth = e.target.value;
    },
    handleMouseDown(e) {
      const { offsetX, offsetY } = e;
      if (this.isInsertImage) {
        this.insertImage(offsetX, offsetY);
        this.isInsertImage = false;
        return;
      }
      this.isPainting = true;
      this.prevPos = { offsetX, offsetY };
    },
    endPaintEvent() {
      if (this.isPainting) {
        this.isPainting = false;
      }
    },
    handleMouseMove(e) {
      if (this.isPainting) {
        const { offsetX, offsetY } = e;
        const offSetData = { offsetX, offsetY };
        const positionInfo = {
          start: { ...this.prevPos },
          stop: { ...offSetData },
        };
        this.line = this.line.concat(positionInfo);
        this.paint(this.prevPos, offSetData, this.color);
      }
    },
    paint(prevPosition, currPosition, strokeStyle) {
      const { offsetX, offsetY } = currPosition;
      const { offsetX: x, offsetY: y } = prevPosition;
      this.ctx.beginPath();
      this.ctx.strokeStyle = strokeStyle;
      this.ctx.moveTo(x, y);
      this.ctx.lineTo(offsetX, offsetY);
      this.ctx.stroke();
      this.prevPos = { offsetX, offsetY };
    },
  },
};
</script>

<style scoped>
#app {
  font-family: 'Avenir', Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  color: #2c3e50;
  margin-top: 60px;
}
canvas {
  background: #fff;
}
.main {
  display: flex;
  justify-content: center;
}
.color-guide {
  margin: 20px 40px;
}
h5 {
  margin-top: 10px;
  margin-bottom: 10px;
}
.btn {
  border-radius: 2px;
  border: solid 1px;
  background-color: #fff;
  padding: 5px;
  margin-top: 5px;
}
</style>
