<template>
  <div class="container">
    <h2 class="title">Peta Interaktif Kabupaten Sidoarjo</h2>

    <div class="layout">
      <div id="map"></div>

      <div class="panel" v-if="datasets.length">
        <h3>{{ selectedKecamatan }}</h3>
        <p class="subtitle">Dataset tersedia</p>

        <ul>
          <li v-for="(d, i) in datasets" :key="i">
            {{ d.nama }}
            <span>{{ d.tahun }}</span>
          </li>
        </ul>
      </div>
    </div>
  </div>
</template>

<script>
import mapboxgl from "mapbox-gl";

export default {
  data() {
    return {
      map: null,
      hoverPopup: null,
      selectedKecamatan: "",
      datasets: [],
    };
  },

  mounted() {
    mapboxgl.accessToken =
      "pk.eyJ1IjoiZGVzdHJpc3RpZmFuaXYiLCJhIjoiY21qYjRhamxlMGN4eDNlcHJmNTE3NGszbSJ9.oUFP3v5QCrRnReimbnnlgA";

    this.map = new mapboxgl.Map({
      container: "map",
      style: "mapbox://styles/mapbox/streets-v12",
      center: [112.7175, -7.4469],
      zoom: 10,
    });

    this.map.on("load", async () => {
      const geojson = await fetch("/geojson/kecamatan_sidoarjo.geojson").then(
        (r) => r.json()
      );

      this.map.addSource("kecamatan", {
        type: "geojson",
        data: geojson,
      });

      this.addLayers();
      this.addEvents();
    });
  },

  methods: {
    addLayers() {
      // FILL TIPIS
      this.map.addLayer({
        id: "kecamatan-fill",
        type: "fill",
        source: "kecamatan",
        paint: {
          "fill-color": "#1976d2",
          "fill-opacity": 0.12,
        },
      });

      // BORDER NORMAL
      this.map.addLayer({
        id: "kecamatan-line",
        type: "line",
        source: "kecamatan",
        paint: {
          "line-color": "#1565c0",
          "line-width": 1,
        },
      });

      // BORDER HOVER (KKP STYLE)
      this.map.addLayer({
        id: "kecamatan-hover",
        type: "line",
        source: "kecamatan",
        paint: {
          "line-color": "#ff9800",
          "line-width": 3,
        },
        filter: ["==", "kode", ""],
      });
    },

    addEvents() {
      // HOVER
      this.map.on("mousemove", "kecamatan-fill", (e) => {
        this.map.getCanvas().style.cursor = "pointer";

        const props = e.features[0].properties;

        this.map.setFilter("kecamatan-hover", [
          "==",
          "kode",
          props.kode,
        ]);

        if (!this.hoverPopup) {
          this.hoverPopup = new mapboxgl.Popup({
            closeButton: false,
            closeOnClick: false,
            offset: 8,
          });
        }

        this.hoverPopup
          .setLngLat(e.lngLat)
          .setHTML(`
            <div class="popup">
              <strong>${props.nama}</strong><br/>
              ${props.jumlah_dataset || 0} Dataset
            </div>
          `)
          .addTo(this.map);
      });

      // LEAVE
      this.map.on("mouseleave", "kecamatan-fill", () => {
        this.map.getCanvas().style.cursor = "";
        this.map.setFilter("kecamatan-hover", ["==", "kode", ""]);
        if (this.hoverPopup) this.hoverPopup.remove();
      });

      // CLICK
      this.map.on("click", "kecamatan-fill", (e) => {
        const props = e.features[0].properties;

        this.selectedKecamatan = props.nama;

        // nanti ganti API
        this.datasets = [
          { nama: "Jumlah Penduduk", tahun: 2024 },
          { nama: "Jumlah Sekolah", tahun: 2023 },
          { nama: "Fasilitas Kesehatan", tahun: 2022 },
        ];
      });
    },
  },
};
</script>

<style scoped>
.container {
  padding: 16px;
  font-family: "Poppins", sans-serif;
}

.layout {
  display: flex;
  gap: 16px;
  height: 600px;
}

#map {
  flex: 3;
  border-radius: 12px;
  border: 1px solid #ddd;
}

.panel {
  flex: 1;
  background: white;
  border-radius: 12px;
  padding: 16px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
}

.mapboxgl-popup-content {
  padding: 6px 8px;
  font-size: 12px;
}
</style>
