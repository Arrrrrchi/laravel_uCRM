<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { reactive, onMounted } from 'vue';
import { getToday } from '@/common';
import axios from 'axios';
import Chart from '@/Components/Chart.vue';
import ResultTable from '@/Components/ResultTable.vue';

onMounted(() => {
    form.startDate = getToday();
    form.endDate = getToday();
});

const form = reactive({
    startDate: null,
    endDate: null,
    type: 'perDay',
});

const data = reactive({})

const getData = async () => {
    try {
        await axios.get('/api/analysis/', {
            params: {
                startDate: form.startDate,
                endDate: form.endDate,
                type: form.type
            }
        }).then(res => {
            data.data = res.data.data;
            data.labels = res.data.labels;
            data.totals = res.data.totals;
            data.type = res.data.type;
            console.log(res.data);
        });
    } catch (e) {
        console.log(e.message);
    }
}



</script>

<template>
    <Head title="データ分析" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">データ分析</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <form @submit.prevent="getData">
                            <h3>分析方法</h3>
                            <br>
                            <div class="flex items-center">
                                <input type="radio" class="mr-1" v-model="form.type" value="perDay" checked><span class="mr-4">日別</span>
                                <input type="radio" class="mr-1" v-model="form.type" value="perMonth"><span class="mr-4">月別</span>
                                <input type="radio" class="mr-1" v-model="form.type" value="perYear"><span class="mr-4">年別</span>
                                <input type="radio" class="mr-1" v-model="form.type" value="decile"><span class="mr-4">デシル分析</span>
                            </div>
                            <br>
                            <div class="flex items-center">
                                From: <input type="date" name="startDate" v-model="form.startDate" class="mr-4">
                                To: <input type="date" name="endDate" v-model="form.endDate" class="mr-10">
                                <button class="text-white bg-indigo-500 border-0 py-2 px-8 focus:outline-none hover:bg-indigo-600 rounded text-lg">分析する</button>
                            </div>
                        </form>
                        <div v-show="data.data" class="mt-6">
                            <Chart :data="data" />
                        </div>
                        <ResultTable :data="data" />
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
