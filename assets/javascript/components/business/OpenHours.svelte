<script lang='ts'>
    import { onMount } from "svelte";

    let days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday']

    onMount(() => {
        var select_open_1 = document.getElementById('select-open-1') as any;
        var select_close_1 = document.getElementById('select-close-1') as any;
        var select_open_2 = document.getElementById('select-open-2') as any;
        var select_close_2 = document.getElementById('select-close-2') as any;
        var select_open_3 = document.getElementById('select-open-3') as any;
        var select_close_3 = document.getElementById('select-close-3') as any;
        var select_open_4 = document.getElementById('select-open-4') as any;
        var select_close_4 = document.getElementById('select-close-4') as any;
        var select_open_5 = document.getElementById('select-open-5') as any;
        var select_close_5 = document.getElementById('select-close-5') as any;
        var select_open_6 = document.getElementById('select-open-6') as any;
        var select_close_6 = document.getElementById('select-close-6') as any;
        var select_open_7 = document.getElementById('select-open-7') as any;
        var select_close_7 = document.getElementById('select-close-7') as any;
        var hiddenInput = document.getElementById('hours-input') as any;

        select_open_1.onchange = function () {
            var temp = hiddenInput.value.split(',');
            temp[0] = select_open_1.value + " - " + select_close_1.value;
            hiddenInput.value = temp.join(',');
            if (select_open_1.value == "Closed") {
                select_close_1.style.display = 'none';
                var temp = hiddenInput.value.split(',');
                temp[0] = "12:00 AM - 12:00 AM";
                hiddenInput.value = temp.join(',');                        
            } else {
                select_close_1.style.display = 'block';
            }
        }

        select_open_2.onchange = function () {
            var temp = hiddenInput.value.split(',');
            temp[1] = select_open_2.value + " - " + select_close_2.value;
            hiddenInput.value = temp.join(',');
            if (select_open_2.value == "Closed") {
                select_close_2.style.display = 'none';
                var temp = hiddenInput.value.split(',');
                temp[1] = "12:00 AM - 12:00 AM";
                hiddenInput.value = temp.join(',');
            } else {
                select_close_2.style.display = 'block';
            }
        }

        select_open_3.onchange = function () {
            var temp = hiddenInput.value.split(',');
            temp[2] = select_open_3.value + " - " + select_close_3.value;
            hiddenInput.value = temp.join(',');
            if (select_open_3.value == "Closed") {
                select_close_3.style.display = 'none';
                var temp = hiddenInput.value.split(',');
                temp[2] = "12:00 AM - 12:00 AM";
                hiddenInput.value = temp.join(',');
            } else {
                select_close_3.style.display = 'block';
            }
        }

        select_open_4.onchange = function () {
            var temp = hiddenInput.value.split(',');
            temp[3] = select_open_4.value + " - " + select_close_4.value;
            hiddenInput.value = temp.join(',');
            if (select_open_4.value == "Closed") {
                select_close_4.style.display = 'none';
                var temp = hiddenInput.value.split(',');
                temp[3] = "12:00 AM - 12:00 AM";
                hiddenInput.value = temp.join(',');
            } else {
                select_close_4.style.display = 'block';
            }
        }

        select_open_5.onchange = function () {
            var temp = hiddenInput.value.split(',');
            temp[4] = select_open_5.value + " - " + select_close_5.value;
            hiddenInput.value = temp.join(',');
            if (select_open_5.value == "Closed") {
                select_close_5.style.display = 'none';
                var temp = hiddenInput.value.split(',');
                temp[4] = "12:00 AM - 12:00 AM";
                hiddenInput.value = temp.join(',');
            } else {
                select_close_5.style.display = 'block';
            }
        }

        select_open_6.onchange = function () {
            var temp = hiddenInput.value.split(',');
            temp[5] = select_open_6.value + " - " + select_close_6.value;
            hiddenInput.value = temp.join(',');
            if (select_open_6.value == "Closed") {
                select_close_6.style.display = 'none';
                var temp = hiddenInput.value.split(',');
                temp[5] = "12:00 AM - 12:00 AM";
                hiddenInput.value = temp.join(',');
            } else {
                select_close_6.style.display = 'block';
            }
        }

        select_open_7.onchange = function () {
            var temp = hiddenInput.value.split(',');
            temp[6] = select_open_7.value + " - " + select_close_7.value;
            hiddenInput.value = temp.join(',');
            if (select_open_7.value == "Closed") {
                select_close_7.style.display = 'none';
                var temp = hiddenInput.value.split(',');
                temp[6] = "12:00 AM - 12:00 AM";
                hiddenInput.value = temp.join(',');
            } else {
                select_close_7.style.display = 'block';
            }
        }

        select_close_1.onchange = function () {
            var temp = hiddenInput.value.split(',');
            temp[0] = select_open_1.value + " - " + select_close_1.value;
            hiddenInput.value = temp.join(',');
        }

        select_close_2.onchange = function () {
            var temp = hiddenInput.value.split(',');
            temp[1] = select_open_2.value + " - " + select_close_2.value;
            hiddenInput.value = temp.join(',');
        }

        select_close_3.onchange = function () {
            var temp = hiddenInput.value.split(',');
            temp[2] = select_open_3.value + " - " + select_close_3.value;
            hiddenInput.value = temp.join(',');
        }

        select_close_4.onchange = function () {
            var temp = hiddenInput.value.split(',');
            temp[3] = select_open_4.value + " - " + select_close_4.value;
            hiddenInput.value = temp.join(',');
        }

        select_close_5.onchange = function () {
            var temp = hiddenInput.value.split(',');
            temp[4] = select_open_5.value + " - " + select_close_5.value;
            hiddenInput.value = temp.join(',');
        }

        select_close_6.onchange = function () {
            var temp = hiddenInput.value.split(',');
            temp[5] = select_open_6.value + " - " + select_close_6.value;
            hiddenInput.value = temp.join(',');
        }

        select_close_7.onchange = function () {
            var temp = hiddenInput.value.split(',');
            temp[6] = select_open_7.value + " - " + select_close_7.value;
            hiddenInput.value = temp.join(',');
        }

    })
</script>

{#each days as day, index}
    <div class="flex items-center my-2 space-x-2 justify-start">
        <label class="w-24">{day}</label>
        <select 
            id="{`select-open-${index + 1}`}" 
            class="border rounded py-1 px-3 border-zinc-400">
            <option>Closed</option>
            {#each Array(24) as _, i}
                <option>{i == 0 ? '12:00 AM' : (i <= 12 ? `${i}:00 AM` : (i == 12 ? `${i}:00 PM` : `${(i - 12)}:00 PM`))}</option>
            {/each}
        </select>
        <select
            id="{`select-close-${index + 1}`}" 
            class="border rounded py-1 px-3 border-zinc-400 hidden">
            {#each Array(24) as _, i}
                <option>{i == 0 ? '12:00 AM' : (i <= 12 ? `${i}:00 AM` : (i == 12 ? `${i}:00 PM` : `${(i - 12)}:00 PM`))}</option>
            {/each}
        </select>
    </div>
{/each}