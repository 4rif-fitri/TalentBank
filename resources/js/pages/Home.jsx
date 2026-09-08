import { useEffect, useState } from "react";
import api from "../lib/axios";

export default function Home() {
    const [data, setData] = useState([]);

    useEffect(() => {
        getData();
    }, []);

    const getData = async () => {
        try {
            const response = await api.get(
                "/api/profile/getAllStudentUserProfiles",
            );

            console.log(response.data);

        } catch (error) {
            console.error(error);
        }
    };

    return (
        <div>
            <h1>TalentBank</h1>


        </div>
    );
}
