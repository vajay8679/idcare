/**
 * Sample React Native App
 * https://github.com/facebook/react-native
 *
 * @format
 * @flow
 */

import React from 'react';
// import {
//   SafeAreaView,
//   StyleSheet,
//   ScrollView,
//   View,
//   Text,
//   StatusBar,
// } from 'react-native';


import { View,Container, Header, Content, Footer, FooterTab, Button, Text,Item,Input,Form } from 'native-base';
import {createAppContainer} from 'react-navigation';
import {createStackNavigator} from 'react-navigation-stack';

import Home from './Home';
import Login from './Login';
import Roleselection from './Roleselection';
import Newentry from './Newentry';
import Patientlist from './Patientlist';
import PatientcurrentDetail from './PatientcurrentDetail';
import EditPentry from './EditPentry';
import Forgotpassword from './Forgotpassword';
import Notificationlist from './Notificationlist';
import Admingraph from './Admingraph';
import DignosisList from './DignosisList';

import Splash from './Splash';

const MainNavigator = createStackNavigator({


 Splash: {screen: Splash,
  navigationOptions : {
  header: null,
  }
  },

 Roleselection: {screen: Roleselection,
  navigationOptions : {
  header: null,
  }
  },
  Login: {screen: Login,
  navigationOptions : {
  header: null,
  }
  },
  Forgotpassword: {screen: Forgotpassword,
    navigationOptions : {
    header: null,
    }
    },
  
  Home: {screen: Home,navigationOptions : {
    header: null,
    }},
    
    Admingraph: {screen: Admingraph,navigationOptions : {
      header: null,
      }},
      
  Newentry: {screen: Newentry,navigationOptions : {
      header: null,
     }},
     EditPentry: {screen: EditPentry,navigationOptions : {
      header: null,
     }},
   Patientlist: {screen: Patientlist,navigationOptions : {
      header: null,
    }},
    Notificationlist: {screen: Notificationlist,navigationOptions : {
      header: null,
    }},
    
    PatientcurrentDetail: {screen: PatientcurrentDetail,navigationOptions : {
      header: null,
    }},
    DignosisList: {screen: DignosisList,navigationOptions : {
      header: null,
    }},
    
     // Newentry: {screen: Newentry},

    

  });

const AppContainer = createAppContainer(MainNavigator);

export default class App extends React.Component {
  render() {
    return <AppContainer />;
  }
}