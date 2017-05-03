<template>
    <tr>
        <td>{{ wrestler.id }}</td>
        <td>{{ wrestler.name }}</td>
        <td>
            <a class="btn btn-sm btn-icon btn-flat btn-default" :href="`${url}/${wrestler.id}/edit`" data-toggle="tooltip" data-original-title="Edit">
                <i class="icon wb-wrench" aria-hidden="true"></i>
            </a>
            <a class="btn btn-sm btn-icon btn-flat btn-default" :href="`${url}/${wrestler.id}`" data-toggle="tooltip" data-original-title="Show">
                <i class="icon wb-eye" aria-hidden="true"></i>
            </a>
            <button class="btn btn-sm btn-icon btn-flat btn-default" type="button" data-toggle="tooltip" data-original-title="Delete">
                <i class="icon wb-close" aria-hidden="true"></i>
            </button>
        </td>
    </tr>
</template>

<script>
    export default {
        data() {
            return {

            }
        },

        props: [
            'wrestler',
            'url',
        ]
    }
</script>