<script lang="ts">
    import MenuIcon from "./MenuIcon.svelte";
    import Server from "./Server.svelte";


    export let servers = [{name: "", id: "", icon: "", verified: false}];
</script>


<div class="flex flex-col h-full overflow-hidden bg-[#202324] w-[72px] items-center">
    <MenuIcon />
    {#each servers as server}
        <Server {server} />
    {/each}
</div>