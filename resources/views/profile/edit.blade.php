@extends('layouts.app')

@section('title', 'Edit Profile')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold">Informasi Profil</h3>
            </div>
            <div class="p-6">
                <form method="post" action="{{ route('profile.update') }}" class="space-y-6">
                    @csrf
                    @method('patch')
                    @if ($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Nama</label>
                        <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" 
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                               required autofocus autocomplete="name">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                        <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                               required autocomplete="email">
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="address" class="block text-sm font-medium text-gray-700">Alamat</label>
                        <textarea id="address" name="address" rows="3"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('address', $user->address) }}</textarea>
                        @error('address')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700">Nomor Telepon</label>
                        <input id="phone" name="phone" type="text" value="{{ old('phone', $user->phone) }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        @error('phone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Lokasi di Peta</label>
                        <div id="map" class="h-96 w-full rounded-lg border border-gray-300" style="z-index: 0;"></div>
                        <input type="hidden" id="latitude" name="latitude" value="{{ old('latitude', $user->latitude) }}">
                        <input type="hidden" id="longitude" name="longitude" value="{{ old('longitude', $user->longitude) }}">
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" 
                                class="inline-flex justify-center rounded-md border border-transparent bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            Simpan
                        </button>
                    </div>
                </form>
                @if(auth()->user()->role === 'petani')
                    <div class="bg-white shadow-md rounded-lg overflow-hidden mt-6">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-semibold">Status Verifikasi Lahan</h3>
                        </div>
                        <div class="p-6">
                            @if(auth()->user()->land_area_verified)
                                <div class="flex items-center p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50" role="alert">
                                    <svg class="flex-shrink-0 inline w-5 h-5 mr-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z"/>
                                    </svg>
                                    <span class="font-medium">Lahan terverifikasi!</span>
                                    <div class="ml-2">Luas lahan Anda telah diverifikasi oleh admin dan kuota pupuk bersubsidi telah ditetapkan.</div>
                                </div>
                            @elseif(auth()->user()->hasPendingVerification())
                                <div class="flex items-center p-4 mb-4 text-sm text-blue-800 rounded-lg bg-blue-50" role="alert">
                                    <svg class="w-5 h-5 mr-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM10 15a1 1 0 1 1 0-2 1 1 0 0 1 0 2Zm1-4a1 1 0 0 1-2 0V6a1 1 0 0 1 2 0v5Z"/>
                                    </svg>
                                    <span class="font-medium">Verifikasi dalam proses!</span>
                                    <div class="ml-2">Permintaan verifikasi lahan Anda sedang diproses. Mohon tunggu konfirmasi dari admin.</div>
                                </div>
                            @else
                                <div class="flex items-center p-4 mb-4 text-sm text-yellow-800 rounded-lg bg-yellow-50" role="alert">
                                    <svg class="flex-shrink-0 inline w-5 h-5 mr-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM10 15a1 1 0 1 1 0-2 1 1 0 0 1 0 2Zm1-4a1 1 0 0 1-2 0V6a1 1 0 0 1 2 0v5Z"/>
                                    </svg>
                                    <span class="font-medium">Lahan belum terverifikasi!</span>
                                    <div class="ml-2">Untuk mendapatkan kuota pupuk bersubsidi, silakan upload KTP Anda dan isi luas lahan yang valid.</div>
                                </div>
                            @endif

                            @if(!auth()->user()->land_area_verified && !auth()->user()->hasPendingVerification())
                            <form method="post" action="{{ route('profile.verify-land') }}" class="space-y-6" enctype="multipart/form-data">
                                @csrf
                                
                                <div>
                                    <label for="land_area" class="block text-sm font-medium text-gray-700">Luas Lahan (hektar)</label>
                                    <input id="land_area" name="land_area" type="number" step="0.01" min="0.01" max="100"
                                            value="{{ old('land_area', auth()->user()->land_area) }}"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                            required>
                                    <p class="mt-1 text-sm text-gray-500">
                                        Luas lahan akan digunakan untuk menghitung kuota pupuk Anda secara otomatis.
                                    </p>
                                    @error('land_area')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="ktp_image" class="block text-sm font-medium text-gray-700">Upload KTP</label>
                                    <input type="file" id="ktp_image" name="ktp_image" accept="image/*"
                                        class="mt-1 block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none"
                                        required>
                                    <p class="mt-1 text-sm text-gray-500">
                                        Upload foto KTP asli Anda untuk verifikasi luas lahan. Format yang diterima: JPG, PNG (maks 2MB).
                                    </p>
                                    @error('ktp_image')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="flex justify-end">
                                    <button type="submit" 
                                            class="inline-flex justify-center rounded-md border border-transparent bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                        Ajukan Verifikasi
                                    </button>
                                </div>
                            </form>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold">Update Password</h3>
            </div>
            <div class="p-6">
                <form method="post" action="{{ route('password.update') }}" class="space-y-6">
                    @csrf
                    @method('put')
                    
                    <div>
                        <label for="current_password" class="block text-sm font-medium text-gray-700">Password Saat Ini</label>
                        <input id="current_password" name="current_password" type="password"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                               autocomplete="current-password">
                        @error('current_password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">Password Baru</label>
                        <input id="password" name="password" type="password"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                               autocomplete="new-password">
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Konfirmasi Password</label>
                        <input id="password_confirmation" name="password_confirmation" type="password"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                               autocomplete="new-password">
                        @error('password_confirmation')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-end">
                        <button type="submit"
                                class="inline-flex justify-center rounded-md border border-transparent bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            Update Password
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-red-600">Hapus Akun</h3>
            </div>
            <div class="p-6">
                <p class="text-sm text-gray-600">
                    Setelah akun Anda dihapus, semua data dan sumber daya terkait akan dihapus secara permanen. 
                </p>

                <button
                    x-data=""
                    x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
                    class="mt-4 inline-flex justify-center rounded-md border border-transparent bg-red-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2"
                >
                    Hapus Akun
                </button>

                <!-- Modal -->
                <div
                    x-data="{ show: false, name: '' }"
                    x-show="show"
                    x-on:open-modal.window="show = ($event.detail === 'confirm-user-deletion')"
                    x-on:close.stop="show = false"
                    x-on:keydown.escape.window="show = false"
                    x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-50"
                    style="display: none;"
                >
                    <div
                        x-on:click.stop
                        x-trap.noscroll.inert="show"
                        class="w-full max-w-md overflow-hidden rounded-lg bg-white p-6 shadow-xl"
                    >
                        <h2 class="text-lg font-medium text-gray-900">
                            Apakah Anda yakin ingin menghapus akun?
                        </h2>

                        <p class="mt-1 text-sm text-gray-600">
                            Setelah akun Anda dihapus, semua data terkait akan dihapus secara permanen.
                        </p>

                        <form method="post" action="{{ route('profile.destroy') }}" class="mt-6">
                            @csrf
                            @method('delete')

                            <div>
                                <label for="password" class="text-sm font-medium text-gray-700">Password</label>
                                <input
                                    id="password"
                                    name="password"
                                    type="password"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    placeholder="Masukkan password untuk konfirmasi"
                                />
                                @error('password', 'userDeletion')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mt-6 flex justify-end">
                                <button
                                    x-on:click.prevent="show = false"
                                    type="button"
                                    class="inline-flex justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-300 focus:ring-offset-2"
                                >
                                    Batal
                                </button>

                                <button
                                    type="submit"
                                    class="ml-3 inline-flex justify-center rounded-md border border-transparent bg-red-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2"
                                >
                                    Hapus Akun
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Default koordinat (Indonesia)
        let defaultLat = {{ $user->latitude ?? -6.200000 }};
        let defaultLng = {{ $user->longitude ?? 106.816666 }};

        // Inisialisasi peta Leaflet
        const map = L.map('map').setView([defaultLat, defaultLng], 12);
        
        // Tambahkan layer OpenStreetMap
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        // Tambahkan marker untuk lokasi yang dipilih
        let marker = L.marker([defaultLat, defaultLng], {
            draggable: true
        }).addTo(map);

        // Update nilai koordinat saat marker dipindahkan
        marker.on('dragend', function(event) {
            let position = marker.getLatLng();
            document.getElementById('latitude').value = position.lat;
            document.getElementById('longitude').value = position.lng;
            
            // Update alamat dari geocoding
            reverseGeocode(position.lat, position.lng);
        });

        // Tambahkan kontrol pencarian lokasi
        const geocoder = L.Control.geocoder({
            defaultMarkGeocode: false
        }).addTo(map);
        
        // Ketika lokasi ditemukan dari pencarian
        geocoder.on('markgeocode', function(event) {
            const result = event.geocode;
            const latlng = result.center;
            
            // Pindahkan marker dan update form
            marker.setLatLng(latlng);
            map.setView(latlng, 16);
            
            document.getElementById('latitude').value = latlng.lat;
            document.getElementById('longitude').value = latlng.lng;
            document.getElementById('address').value = result.name;
        });

        // Fungsi untuk mendapatkan alamat dari koordinat
        function reverseGeocode(lat, lng) {
            fetch(`https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${lat}&lon=${lng}`)
                .then(response => response.json())
                .then(data => {
                    if (data && data.display_name) {
                        document.getElementById('address').value = data.display_name;
                    }
                })
                .catch(error => console.error('Error:', error));
        }

        // Klik pada peta untuk menambahkan marker baru
        map.on('click', function(e) {
            marker.setLatLng(e.latlng);
            document.getElementById('latitude').value = e.latlng.lat;
            document.getElementById('longitude').value = e.latlng.lng;
            reverseGeocode(e.latlng.lat, e.latlng.lng);
        });
        
        // Tambahkan event listener pada input alamat
        const addressInput = document.getElementById('address');
        addressInput.addEventListener('change', function() {
            const address = this.value;
            
            // Cari koordinat untuk alamat
            fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(address)}&limit=1`)
                .then(response => response.json())
                .then(data => {
                    if (data && data.length > 0) {
                        const lat = parseFloat(data[0].lat);
                        const lon = parseFloat(data[0].lon);
                        
                        marker.setLatLng([lat, lon]);
                        map.setView([lat, lon], 16);
                        
                        document.getElementById('latitude').value = lat;
                        document.getElementById('longitude').value = lon;
                    }
                })
                .catch(error => console.error('Error:', error));
        });
    });

    const fixZIndexIssues = () => {
        // Fix untuk navbar
        document.querySelectorAll('nav').forEach(nav => {
            nav.style.zIndex = '1000';
            nav.style.position = 'relative';
        });
        
        // Fix untuk modal dialog
        document.querySelectorAll('[x-show="open"].fixed.inset-0').forEach(modal => {
            modal.style.zIndex = '1500';
        });
        
        // Kurangi z-index pada kontainer peta
        document.querySelectorAll('.leaflet-container').forEach(container => {
            container.style.zIndex = '100';
        });
    };

    // Jalankan saat peta dimuat
    map.on('load', fixZIndexIssues);
    // Jalankan juga sekarang
    fixZIndexIssues();
</script>
@endsection