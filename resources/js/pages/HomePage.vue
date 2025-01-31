<template>
    <div class="w-full bg-black">
        <Hero />

        <CitySelection />
        <AboutWolt />
        <InfoSection />
        <DownloadApp />
        <JobOffer />

    </div>
</template>

<script>
import AboutWolt from "../components/AboutWolt.vue";
import CitySelection from "../components/CitySelection.vue";
import DownloadApp from "../components/DownloadApp.vue";
import Hero from "../components/Hero.vue";
import InfoSection from "../components/InfoSection.vue";
import JobOffer from "../components/JobOffer.vue";



export default {
    name: "HomePage",
    components: {
        Hero,

        CitySelection,
        AboutWolt,
        InfoSection,
        DownloadApp,
        JobOffer
    },
};
</script>
