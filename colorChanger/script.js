// Fungsi untuk memeriksa apakah warna valid
        function isValidColor(color) {
            const s = new Option().style;
            s.color = color;
            return s.color !== '';
        }

        // Fungsi untuk memperbarui kotak warna
        function updateColorBox(color) {
            const colorBox = document.getElementById('colorBox');
            const colorName = document.getElementById('colorName');
            const colorHex = document.getElementById('colorHex');

            // Set background color dari kotak
            colorBox.style.backgroundColor = color;

            // Jika input adalah kode HEX
            if (color.startsWith('#')) {
                colorName.textContent = getColorNameFromHex(color); // Menampilkan nama warna dari HEX
                colorHex.textContent = color.toUpperCase(); // Menampilkan kode HEX
            } else {
                colorName.textContent = color.charAt(0).toUpperCase() + color.slice(1); // Menampilkan nama warna
                colorHex.textContent = getColorHex(color); // Menampilkan kode HEX dari nama warna
            }
        }

        // Fungsi untuk mendapatkan nama warna dari kode HEX
        function getColorNameFromHex(hex) {
            const colors = {
                "#FF0000": "Red",
                "#0000FF": "Blue",
                "#008000": "Green",
                "#FFFF00": "Yellow",
                "#FFA500": "Orange",
                "#800080": "Purple",
                "#00FFFF": "Cyan",
                "#FFC0CB": "Pink",
                "#A52A2A": "Brown",
                "#808080": "Gray",
                "#000000": "Black",
                "#FFFFFF": "White"
                // Tambahkan lebih banyak warna sesuai kebutuhan
            };
            return colors[hex.toUpperCase()] || "Unknown Color";
        }

        // Fungsi untuk mendapatkan kode HEX dari nama warna
        function getColorHex(color) {
            const ctx = document.createElement('canvas').getContext('2d');
            ctx.fillStyle = color;
            return ctx.fillStyle.toUpperCase();
        }

        // Event listener untuk input warna
        document.getElementById('colorInput').addEventListener('input', function() {
            const color = this.value.trim();
            if (isValidColor(color)) {
                updateColorBox(color);
            }
        });

        // Event listener untuk tombol toggle warna teks
        document.getElementById('toggleTextColorButton').addEventListener('click', function() {
            const colorBox = document.getElementById('colorBox');
            const currentColor = window.getComputedStyle(colorBox).color;
            colorBox.style.color = currentColor === 'rgb(0, 0, 0)' ? '#FFF' : '#000';
        });