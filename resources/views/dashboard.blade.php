<x-app-layout>
  <x-slot name="header">
    <title>Dashboard Perindustrian</title>
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
  </x-slot>

  <div class="py-12" style="background: linear-gradient(135deg, #daf0dd, #51cf7b); min-height:100vh;">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

      <div class="grid grid-cols-1 gap-8">

        <!-- KBLI -->
        <a href="{{ route('dasbor.kbli') }}"
           class="group block w-full rounded-3xl h-36 flex items-center justify-center
                  border-8 border-red-600 shadow-lg bg-white/90
                  hover:bg-red-600 hover:text-white hover:shadow-2xl transition duration-300">
          <span class="text-4xl font-semibold text-gray-900 group-hover:text-white">KBLI</span>
        </a>

        <!-- Skala Usaha -->

      </div>
    </div>
  </div>

    </div>
  </div>
</x-app-layout>
