import { usePage } from "@inertiajs/react";
import { useEffect, useState } from "react";
import api from "../lib/axios";

const createPosition = async () => {
    try {
        const response = await api.post("/api/positions/store", {
            organization_id: 8,
            position_title: "Software Developer",
            employment_type: "Internship",
            department: "IT",
            work_location: "Melaka",
            vacancies: 2,
            description: "Develop web applications",
        });

        console.log("Success:", response.data);
    } catch (error) {
        console.error("Error:", error.response?.data);
    }
};

createPosition();

export default function Home() {
    const [data, setData] = useState([]);
    const { auth, session } = usePage().props;

    console.log("User:", auth);
    console.log("Profile ID:", session);
    console.log("Roles:", session);

    // useEffect(() => {
    //     getData();
    // }, []);

    // const getData = async () => {
    //     try {
    //         const response = await api.get(
    //             "/api/profile/getAllStudentUserProfiles",
    //         );

    //         console.log(response.data);
    //     } catch (error) {
    //         console.error(error);
    //     }
    // };

    return (
        <div>
            <h1>TalentBank</h1>
        </div>
    );
}
