<template>
  <div class="min-h-screen bg-slate-300">
    <header
      class="py-5 absolute z-10 w-full top-0 bg-gradient-to-r from-blue-500/75 from-30% via-violet-500/50 shadow-md shadow-blue-400/25 via-60% to-orange-500/25 to-90% font-semibold text-white text-xl px-10"
    >
      Login
    </header>
    <div class="min-h-screen">
      <div class="flex justify-center items-center h-full">
        <div class="sm:flex gap-10 py-10">
          <div class="my-10 pt-20 px-5">
            <h1
              class="text-3xl font-extrabold py-3 text-slate-800 text-transparent bg-gradient-to-tr from-blue-500 from-40% via-violet-500 via-50% to-orange-500 to-80% bg-clip-text"
            >
              Order na tabi pang patak patak lang oh.
            </h1>
            <img src="../assets/login.svg" alt="" class="sm:w-full sm:h-full" />
          </div>
          <div class="flex justify-center items-center">
            <div
              class="p-[1px] shadow-xl shadow-blue-500/25 rounded-xl bg-gradient-to-tr from-blue-500 via-violet-500 to-orange-500"
            >
              <div
                class="bg-slate-300 flex justify-center items-center rounded-xl py-5 w-[400px] px-3"
              >
                <router-view> </router-view>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
