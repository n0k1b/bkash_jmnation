require('./bootstrap');
import 'regenerator-runtime/runtime';
import axios from 'axios';
// const axiosIns = axios.create({
//     // You can add your headers here
//     // ================================
//     // baseURL: 'https://some-domain.com/api/',
//     // timeout: 1000,
//     // headers: {'X-Custom-Header': 'foobar'}
//     baseURL: process.env.MIX_APP_URL,
//     timeout: 50000,
//     headers: {
//       Authorization: `Bearer ${localStorage.getItem('token')}`,
//       userId: localStorage.getItem('userId'),
//       userName: localStorage.getItem('name'),
//       type: localStorage.getItem('type'),
//     },
//   })
