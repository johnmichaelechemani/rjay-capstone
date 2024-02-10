<template>
  <div class="mx-4">
    <div class="py-3 px-10 font-bold text-2xl text-slate-700">
      <h1>Add Products</h1>
    </div>
    <div>
      <div class="flex justify-end">
        <h1
          class="bg-blue-400/20 text-blue-600 px-3 font-medium text-sm py-1 rounded-full mb-2 shadow"
        >
          Save Product
        </h1>
      </div>
    </div>
    <div class="sm:flex gap-5">
      <div
        class="bg-slate-400/10 rounded-md h-full w-1/2 text-slate-700 shadow"
      >
        <div class="p-2">
          <h1 class="text-base font-semibold">Add Image</h1>
          <div class="m-2">
            <div
              class="h-32 w-full flex justify-center rounded-md items-center bg-slate-50 border"
            >
              <p class="text-sm font-medium">Click to upload a image</p>
            </div>
          </div>
        </div>
      </div>
      <div
        class="bg-slate-400/10 rounded-md h-full w-full text-slate-700 shadow"
      >
        <div class="p-2">
          <h1 class="text-base font-semibold">General Information</h1>
          <p class="text-xs">Last 7 Days</p>
        </div>
      </div>
    </div>
  </div>
</template>
